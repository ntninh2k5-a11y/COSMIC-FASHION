<?php
$file = 'resources/views/admin/reports/index.blade.php';
$content = file_get_contents($file);

$replacement = <<<PHP
        @php
            \$today = \Carbon\Carbon::today()->toDateString();
            \$sevenDaysAgo = \Carbon\Carbon::today()->subDays(6)->toDateString();
            \$monthStart = \Carbon\Carbon::today()->startOfMonth()->toDateString();
            \$yearStart = \Carbon\Carbon::today()->startOfYear()->toDateString();
            
            \$reqRange = request('range');
        @endphp
        <div class="d-flex gap-2 ms-auto">
            <a href="{{ route('admin.reports.index', ['from' => \$today, 'to' => \$today, 'range' => 'today']) }}"
               class="btn-outline-admin {{ \$reqRange == 'today' ? 'active-filter' : (empty(\$reqRange) && \$from == \$today && \$to == \$today ? 'active-filter' : '') }}" style="font-size:0.8rem;padding:6px 14px;">Hôm nay</a>
            <a href="{{ route('admin.reports.index', ['from' => \$sevenDaysAgo, 'to' => \$today, 'range' => '7days']) }}"
               class="btn-outline-admin {{ \$reqRange == '7days' ? 'active-filter' : (empty(\$reqRange) && \$from == \$sevenDaysAgo && \$to == \$today ? 'active-filter' : '') }}" style="font-size:0.8rem;padding:6px 14px;">7 ngày</a>
            <a href="{{ route('admin.reports.index', ['from' => \$monthStart, 'to' => \$today, 'range' => 'month']) }}"
               class="btn-outline-admin {{ \$reqRange == 'month' ? 'active-filter' : (empty(\$reqRange) && \$from == \$monthStart && \$to == \$today && \$monthStart != \$today ? 'active-filter' : '') }}" style="font-size:0.8rem;padding:6px 14px;">Tháng này</a>
            <a href="{{ route('admin.reports.index', ['from' => \$yearStart, 'to' => \$today, 'range' => 'year']) }}"
               class="btn-outline-admin {{ \$reqRange == 'year' ? 'active-filter' : (empty(\$reqRange) && \$from == \$yearStart && \$to == \$today && \$yearStart != \$today ? 'active-filter' : '') }}" style="font-size:0.8rem;padding:6px 14px;">Năm nay</a>
        </div>
PHP;

$content = preg_replace('/@php\s+\$today =.*?<\/div>/s', $replacement, $content);
$content = str_replace("type: 'line',", "type: 'bar',", $content);
$content = str_replace("y1: { position: 'right', beginAtZero: true, grid: { display: false } }", "y1: { position: 'right', beginAtZero: true, grid: { display: false }, ticks: { stepSize: 1, precision: 0 } }", $content);

file_put_contents($file, $content);
echo "Done";
