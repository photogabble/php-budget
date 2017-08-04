<?php

use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{

    /**
     * @var \App\Repositories\CategoryRepository
     */
    private $categoryRepository;

    public function __construct(\App\Repositories\CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $defaultCategories = [
            'Miscellaneous',
            'Giving - Charity',
            'Gifts - Birthday',
            'Gifts - Anniversary',
            'Gifts - Wedding',
            'Gifts - Christmas',
            'Gifts - Special Occasion',
            'Food - Groceries',
            'Food - Restaurants',
            'Food - Eating Out',
            'Shelter - Mortgage/Rent Payment',
            'Shelter - Property Taxes',
            'Shelter - Repairs/Maintenance',
            'Shelter - Improvements',
            'Utilities - Electricity',
            'Utilities - Water',
            'Utilities - Gas',
            'Utilities - Garbage',
            'Utilities - Phones',
            'Utilities - Internet',
            'Utilities - TV License',
            'Utilities - Hosting',
            'Clothing - Adults',
            'Clothing - Children\'s',
            'Transportation - Fuel',
            'Transportation - Tires',
            'Transportation - Maintenance',
            'Transportation - Repairs',
            'Transportation - Parking Fees',
            'Medical - Primary Care',
            'Medical - Dental Care',
            'Medical - Speciality Care',
            'Medical - Medication',
            'Insurance - Health',
            'Insurance - Life',
            'Insurance - Homeowners',
            'Insurance - Renters',
            'Insurance - Automotive',
            'Insurance - Identify Theft Protection',
            'Household - Toiletries',
            'Household - Laundry Detergent',
            'Household - Dishwasher Detergent',
            'Household - Cleaning Supplies',
            'Household - Tools',
            'Personal - Gym Memberships',
            'Personal - Hair Cuts',
            'Personal - Salon Services',
            'Personal - Cosmetics',
            'Personal - Babysitter',
            'Personal - Subscriptions',
            'Personal - Jewelry',
            'Debt Reduction - Credit Card',
            'Debt Reduction - Personal Loan',
            'Debt Reduction - Student Loan',
            'Debt Reduction - Other',
            'Savings - Financial Planning',
            'Savings - Investing',
            'Savings - Emergency Fund',
            'Savings - Regular',
            'Education - Children\'s College',
            'Education - College',
            'Education - School Supplies',
            'Education - Books',
            'Education - Conferences',
            'Education - Subscriptions',
            'Fun - Allowance',
            'Fun - Magazines',
            'Fun - Entertainment',
            'Fun - Games',
            'Fun - Vacations',
            'Fun - Subscriptions (e.g Netflix)',
        ];

        foreach ($defaultCategories as $category)
        {
            /** @var \App\Category $record */
            $record = $this->categoryRepository->getNew([
                'name' => $category,
                'default' => ($category === 'Miscellaneous'),
            ]);
            $record->save();
        }
    }
}
