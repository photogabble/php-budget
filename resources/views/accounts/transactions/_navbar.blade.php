<nav class="navbar">
    <div class="container-fluid">
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <div class="navbar-left">
                <form action="{{ route('accounts.transactions.edit-group', $account) }}" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="hidden" id="edit-selected-idList" name="idList" value="" />
                    <button id="edit-selected-btn" class="btn btn-default navbar-btn disabled">Edit Selected</button>
                </form>
            </div>
            <form class="navbar-form navbar-right">
                <div class="input-group">
                    <input class="form-control" placeholder="Search for..." name="s" value="{{ $searchTerm }}">
                    <span class="input-group-btn">
                        @if(strlen($searchTerm) > 0)
                        <a href="{{ route('accounts.transactions', $account) }}" class="btn btn-default" aria-label="Cancel"><span class="glyphicon glyphicon-remove"></span></a>
                        @endif
                        <button class="btn btn-default" aria-label="Start Search"><span class="glyphicon glyphicon-search"></span></button>
                    </span>
                </div><!-- /input-group -->
            </form>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>