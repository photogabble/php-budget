<?php

namespace App\Http\Controllers;

use App\Account;
use App\Category;
use App\Repositories\CategoryRepository;
use App\Repositories\TransactionRepository;
use App\Transaction;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Http\UploadedFile;
use League\Csv\Reader;

class TransactionsImportController extends Controller
{

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;
    /**
     * @var TransactionRepository
     */
    private $transactionRepository;

    public function __construct(CategoryRepository $categoryRepository, TransactionRepository $transactionRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->transactionRepository = $transactionRepository;
    }

    public function import(Account $account)
    {
        return view('accounts.transactions.import')
            ->with('account', $account)
            ->with('actionButtons', [
                ['class' => 'btn-default', 'href' => route('accounts.transactions', $account->id), 'text' => 'Back']
            ]);
    }

    public function importBegin(Account $account, Request $request)
    {
        //
        // Generate hash map of sha1 keys
        //

        $importedRowIds = [];
        $importedHash = [];
        foreach ($account->transactions as $transaction) {

            $importedHash[$transaction->sha1] = true;

        }
        unset($transaction);

        $this->validate($request, [
            'import_file' => 'required',
        ]);

        //
        // Get Default Category
        //
        if (!$defaultCategory = Category::where('default', true)->first()) {
            $defaultCategory = Category::create(['name' => 'Miscellaneous', 'default' => true]);
        }

        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('import_file');

        $reader = Reader::createFromPath($uploadedFile->getPathname());
        $headings = [];

        foreach ($reader as $index => $row) {
            if ($index < 4) {
                continue;
            }
            if ($index === 4) {
                $headings = array_values($row);
                foreach ($headings as $key => $value) {
                    $headings[$key] = strtolower(str_replace(' ', '_', trim($value)));
                }
                continue;
            }

            $row = array_combine($headings, array_map(function ($value) {
                return $this->removeNonUTF8($value);
            }, $row));

            $transaction = new Transaction([
                'category_id' => $defaultCategory->id,
                'date' => $row['date'],
                'transaction_type' => $row['transaction_type'],
                'description' => $row['description'],
                'paid_out' => $row['paid_out'],
                'paid_in' => $row['paid_in'],
                'balance' => 0
            ]);

            $rowHash = $this->transactionRepository->getHashForModel($transaction);

            //
            // If we have previously imported this row for this account, ignore it
            //
            if (isset($importedHash[$rowHash])) {
                continue;
            }

            $transaction->sha1 = $rowHash;
            $transaction = $account->attachTransaction($transaction);
            array_push($importedRowIds, $transaction->id);
        }

        return redirect()->route('accounts.transactions.import.finish', $account->id)
            ->with('importedRowIds', $importedRowIds);
    }

    public function importFinish(Account $account)
    {
        $importedRowIds = \Session::get('importedRowIds', []);

        if (count($importedRowIds) < 1) {
            return redirect()->route('accounts.transactions.import', $account->id)
                ->with('error', 'No new transactions were imported.');
        }

        $imported = $account->transactions()->whereIn('id', $importedRowIds)->get();

        return view('accounts.transactions.import-finish')
            ->with('account', $account)
            ->with('success', "Successfully imported {$imported->count()} transactions for {$account->name}")
            ->with('records', $imported)
            ->with('actionButtons', [
                ['class' => 'btn-default', 'href' => route('accounts.transactions', $account->id), 'text' => 'Back']
            ]);
    }

    private function removeNonUTF8($text)
    {
        $regex = <<<'END'
/
  (
    (?: [\x00-\x7F]                 # single-byte sequences   0xxxxxxx
    |   [\xC0-\xDF][\x80-\xBF]      # double-byte sequences   110xxxxx 10xxxxxx
    |   [\xE0-\xEF][\x80-\xBF]{2}   # triple-byte sequences   1110xxxx 10xxxxxx * 2
    |   [\xF0-\xF7][\x80-\xBF]{3}   # quadruple-byte sequence 11110xxx 10xxxxxx * 3
    ){1,100}                        # ...one or more times
  )
| .                                 # anything else
/x
END;
        return preg_replace($regex, '$1', $text);
    }
}
