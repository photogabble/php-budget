
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

$(function () {
    // We can attach the `fileselect` event to all file inputs on the page
    $(document).on('change', ':file', function () {
        var input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
    });

    // We can watch for our custom `fileselect` event like this
    $(document).ready(function () {
        $(':file').on('fileselect', function (event, numFiles, label) {

            var input = $(this).parents('.input-group').find(':text'),
                log = numFiles > 1 ? numFiles + ' files selected' : label;

            if (input.length) {
                input.val(log);
            } else {
                if (log) alert(log);
            }

        });

        //
        // Initiate Date Range Input
        //

        //var start = moment().subtract(29, 'days');
        //var end = moment();

        $('.reportrange').each(function(k,el){
            var $el = $(el);
            var $input = $($el.data('input-id'));
            var cb = function (start, end) {
                $el.find('span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                $input.val(start.format('X') + ',' + end.format('X'));
            };

            var initialValue = $el.data('value').split(',');
            var start = window.moment.unix(initialValue[0]);
            var end = window.moment.unix(initialValue[1]);

            $el.daterangepicker({
                autoUpdateInput: true,
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [window.moment(), window.moment()],
                    'Yesterday': [window.moment().subtract(1, 'days'), window.moment().subtract(1, 'days')],
                    'Last 7 Days': [window.moment().subtract(6, 'days'), window.moment()],
                    'Last 30 Days': [window.moment().subtract(29, 'days'), window.moment()],
                    'This Month': [window.moment().startOf('month'), window.moment().endOf('month')],
                    'Last Month': [window.moment().subtract(1, 'month').startOf('month'), window.moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);

            $el.on('apply.daterangepicker', function(ev, picker){
                reportFormAjax($input)
            });

            cb(start, end);
        });
    });

    var reportFormAjax = function($input)
    {
        var $form = $input.closest("form");
        var ajax = $.ajax({
            url: $form.data('preview-route'),
            data: $form.serialize(),
            type: 'POST'
        });

        ajax.done(function(data){
            $('#report-output').html(data);
            if (typeof window.executeOnAjaxComplete !== 'undefined') {
                window.executeOnAjaxComplete();
            }
        });
    };

    $(document).on('change', '.report-form input', function(e){
        reportFormAjax($(this))
    });

    $('.delete-button').on('click', function(e){
        e.preventDefault();

        var warningMessage = $(this).data('warning-message');
        if (typeof warningMessage === 'undefined') {
            warningMessage = "Are you sure you want to delete this record?";
        }

        if (confirm(warningMessage)){
            var ajax = $.ajax({
                url: $(this).attr('href'),
                data: {_token: csrfToken},
                type: 'DELETE'
            });

            ajax.fail(function(data){
                alert(data.responseJSON.error);
            });

            ajax.done(function(data){
                window.location = data.location;
            });

        }
    });

    $(".suggest").on('change', function(e){
        var resultTarget = $(this).data('result-target');

        if (typeof resultTarget === 'undefined') { return true; }

        var ajax = $.ajax({
            url: baseUrl + '/categories/engine/suggest',
            data: {_token: csrfToken, q: $(this).val()},
            type: 'POST'
        });

        ajax.done(function(data){
            if (data.suggestion == false) {
                $(resultTarget).text("No suggestions...");
            }else{
                $(resultTarget).text(data.suggestion);
            }
        });

        return true;
    });

    $(".suggest-category").on('click', function(e){
        e.preventDefault();
        $this = $(this);

        var originalText = $this.text();

        $this.attr('disabled', true);
        $this.text('Suggesting...');

        var $querySource = $($this.data('query-source'));
        var $resultTarget = $($this.data('result-target'));

        var ajax = $.ajax({
            url: baseUrl + '/categories/engine/suggest',
            data: {_token: csrfToken, q: $querySource.val()},
            type: 'POST'
        });

        ajax.done(function(data){
            if (data.suggestion == false) {
                alert("No suggestions...");
            }else{
                //alert(data.suggestion);
                if (data.id > 0) {
                    $resultTarget.val(data.id);
                    $resultTarget.trigger("chosen:updated");
                }
            }
            $this.attr('disabled', false);
            $this.text(originalText);
        });
    })

    $(".chartjs__donut-chart").each(function(k, el){
        var $el = $(el);

        var $labels = $el.data('labels');
        var $data   = $el.data('dataset');

        if ($labels.length === 0 && $data.length === 0) {
            return false;
        }

        $el.Chartjs = new Chart($el[0],{
            type: 'doughnut',
            data: {
                labels: $labels,
                datasets: [
                    {
                        data: $data,
                        backgroundColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ]
                    }
                ]
            },
            options: {
                legend : {
                    display: false
                }
            }
        });

    });

    $(".chosen-select").each(function (k, el) {
        var $el = $(el);
        //var $id = $el.attr('name') + '__add-category'
        //$el.parent().append('<div id="' + $id + '">Hello world!</div>');

        $el.chosen({
            allow_single_deselect: true
        });

        $el.parent().find('.chosen-search input[type=text]').keydown(function (evt) {
            var stroke, _ref, target, newString, highlightedList, matchList, chosenList;
            stroke = (_ref = evt.which) != null ? _ref : evt.keyCode;
            target = $(evt.target);

            chosenList = target.parents('.chosen-container').find('.chosen-choices li.search-choice > span').map(function () {
                return $(this).text();
            }).get();

            // get the list of matches from the existing drop-down
            matchList = target.parents('.chosen-container').find('.chosen-results li').map(function () {
                return $(this).text();
            }).get();

            // highlighted option
            highlightedList = target.parents('.chosen-container').find('.chosen-results li.highlighted').map(function () {
                return $(this).text();
            }).get();
            // Get the value which the user has typed in
            newString = $.trim(target.val());

            // if the option does not exists, and the text doesn't exactly match an existing option, and there is not an option highlighted in the list
            if ($.inArray(newString, matchList) < 0 && $.inArray(newString, chosenList) < 0 && highlightedList.length == 0) {

                if (stroke === 13 || stroke === 9) { // On Return or Tab

                    if (typeof $el.data('create-route') !== 'undefined') {
                        if (confirm('Do you want to add the new category "' + newString + '"?')) {
                            var ajaxRequest = $.post(baseUrl + '/categories/create', {
                                _token: csrfToken,
                                name: newString
                            });
                            ajaxRequest.done(function (data) {
                                var newOption = '<option value="' + data.id + '" selected="selected">' + data.name + '</option>';
                                $el.prepend(newOption);
                                $el.trigger("chosen:updated");
                                // tell chosen to close the list box
                                $el.trigger("chosen:close");
                            });

                            ajaxRequest.fail(function (data) {
                                alert('Sorry that new category could not be saved.');
                            });

                        }
                    }else {
                        var newOption = '<option value="' + newString + '" selected="selected">' + newString + '</option>';
                        $el.prepend(newOption);
                        $el.trigger("chosen:updated");
                        // tell chosen to close the list box
                        $el.trigger("chosen:close");
                    }
                }
            }

            // let the event bubble up
            return true;
        });
    });

    var selectedRows = [];

    $(".selectedRowSelector").on('change', function(e){
        selectedRows = [];
        $('.selectedRowSelector:checked').each(function(index, value){
            if (value.value.length > 0) {
                selectedRows.push(value.value);
            }
        });

        if (selectedRows.length > 0) {
            $("#edit-selected-btn").text('Edit Selected ' + selectedRows.length + ' rows').removeClass('disabled');
        } else {
            $("#edit-selected-btn").text('Edit Selected').addClass('disabled');
        }
    });

    $("#edit-selected-btn").on('click', function(e){
        e.preventDefault();
        if ($(this).hasClass('disabled')) { return; }
        $('#edit-selected-idList').val(selectedRows.join());
        $(this).parent().submit();
    });
});