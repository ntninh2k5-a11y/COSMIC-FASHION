import re

file_path = r'C:\xampp\htdocs\laravel\ninh\resources\views\admin\reports\index.blade.php'
with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

filter_code = r'''
        @php
             = \Carbon\Carbon::today()->toDateString();
             = \Carbon\Carbon::today()->subDays(6)->toDateString();
             = \Carbon\Carbon::today()->startOfMonth()->toDateString();
             = \Carbon\Carbon::today()->startOfYear()->toDateString();
            
             = request('range');
        @endphp
        <div class="d-flex gap-2 ms-auto">
            <a href="{{ route('admin.reports.index', ['from' => , 'to' => , 'range' => 'today']) }}"
               class="btn-outline-admin {{  == 'today' ? 'active-filter' : (empty() &&  ==  &&  ==  ? 'active-filter' : '') }}" style="font-size:0.8rem;padding:6px 14px;">Hôm nay</a>
            <a href="{{ route('admin.reports.index', ['from' => , 'to' => , 'range' => '7days']) }}"
               class="btn-outline-admin {{  == '7days' ? 'active-filter' : (empty() &&  ==  &&  ==  ? 'active-filter' : '') }}" style="font-size:0.8rem;padding:6px 14px;">7 ngày</a>
            <a href="{{ route('admin.reports.index', ['from' => , 'to' => , 'range' => 'month']) }}"
               class="btn-outline-admin {{  == 'month' ? 'active-filter' : (empty() &&  ==  &&  ==  &&  !=  ? 'active-filter' : '') }}" style="font-size:0.8rem;padding:6px 14px;">Tháng này</a>
            <a href="{{ route('admin.reports.index', ['from' => , 'to' => , 'range' => 'year']) }}"
               class="btn-outline-admin {{  == 'year' ? 'active-filter' : (empty() &&  ==  &&  ==  &&  !=  ? 'active-filter' : '') }}" style="font-size:0.8rem;padding:6px 14px;">Nam nay</a>
        </div>
'''

content = re.sub(
    r'@php\s+\ =.*?</div>',
    lambda m: filter_code.strip(),
    content,
    flags=re.DOTALL
)

content = content.replace("type: 'line',", "type: 'bar',")
content = content.replace("y1: { position: 'right', beginAtZero: true, grid: { display: false } }", "y1: { position: 'right', beginAtZero: true, grid: { display: false }, ticks: { stepSize: 1, precision: 0 } }")

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)
print("Done")
