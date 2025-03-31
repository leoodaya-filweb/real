<?php

$js = <<< JS
    let start = moment('{$start}');
    let end = moment('{$end}');
    let defaultRanges = {
        'All': [moment('{$all_start}'), moment('{$all_end}')],
        '1st Quarter': [moment('{$currentYear}-01-01'), moment('{$currentYear}-03-31')],
        '2nd Quarter': [moment('{$currentYear}-04-01'), moment('{$currentYear}-06-30')],
        '3rd Quarter': [moment('{$currentYear}-07-01'), moment('{$currentYear}-09-30')],
        '4th Quarter': [moment('{$currentYear}-10-01'), moment('{$currentYear}-12-31')],
       'Today': [moment(), moment()],
       'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
       'Last 7 Days': [moment().subtract(6, 'days'), moment()],
       'Last 30 Days': [moment().subtract(29, 'days'), moment()],
       'This Month': [moment().startOf('month'), moment().endOf('month')],
       'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
       'This Year': [moment().startOf('year'), moment().endOf('year')],
       'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
    }

    let ranges = {$ranges};
    let newRanges = {};
    for(key in ranges) {
        newRanges[ranges[key]] = defaultRanges[ranges[key]];
    }
    $('#{$id}').daterangepicker({
        // buttonClasses: 'btn btn-sm',
        applyClass: 'btn-primary',
        cancelClass: 'btn-secondary',
        startDate: start,
        endDate: end,
        ranges: newRanges
    }, function(start, end, label) {
        $('#{$id} span').html(start.format('MMMM DD, YYYY') + ' - ' + end.format('MMMM DD, YYYY'));
        $('#{$id} input').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
        {$onchange}
    });
    $('#{$id} span').html( start.format('MMMM DD, YYYY') + ' - ' + end.format('MMMM DD, YYYY'));
    $('#{$id} input').val( start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
JS;
$this->registerWidgetJs($widgetFunction, $js);
?>
<?php if ($withTitle): ?>
    <br>
    <p class="font-weight-bold"><?= $title ?></p>
<?php endif ?>
<div class="date-range-search" id="<?= $id ?>">
    <input name="<?= $name ?>" class="form-control pointer"  readonly placeholder="Select Date" type="hidden"  />
    <span class="form-control pointer"> </span>
</div>