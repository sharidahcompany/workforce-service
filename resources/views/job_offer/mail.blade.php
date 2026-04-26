<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; line-height: 1.6; color: #333; }
        .header { text-align: center; margin-bottom: 30px; }
        .section { margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .section-title { color: #2c3e50; font-weight: bold; font-size: 1.2em; }
        .info-grid { width: 100%; border-collapse: collapse; }
        .info-grid td { padding: 8px; vertical-align: top; }
        .label { font-weight: bold; width: 30%; color: #555; }
        .benefits-list { margin-top: 10px; }
        .benefit-item { background: #f9f9f9; padding: 10px; margin-bottom: 5px; border-radius: 5px; }
        .salary-box { background: #e8f4fd; padding: 15px; border-left: 5px solid #2980b9; }
    </style>
</head>
<body>

    <div class="header">
        <h1>عقد عمل وظيفي</h1>
    </div>

    <div class="section">
        <div class="section-title">بيانات الوظيفة العامة</div>
        <table class="info-grid">
            <tr>
                <td class="label">مسمى الوظيفة:</td>
                <td>{{ $jobName }}</td>
            </tr>
            <tr>
                <td class="label">وصف المهام:</td>
                <td>{{ $jobDescription }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">المزايا والحوافز (Benefits)</div>
        <div class="benefits-list">
            @foreach($benefits as $benefit)
                <div class="benefit-item">
                    <strong>{{ $benefit['name'] }}</strong>: 
                    <span>{{ $benefit['description'] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="section">
        <div class="section-title">المقابل المادي</div>
        <div class="salary-box">
            <strong>الراتب الإجمالي المتفق عليه:</strong> 
            <span>{{ number_format($salary, 2) }} جنيه (أو العملة المستخدمة)</span>
        </div>
    </div>

    <div style="margin-top: 50px;">
        <table style="width: 100%;">
            <tr>
                <td>توقيع المدير المسؤول: ....................</td>
                <td style="text-align: left;">توقيع الموظف: ....................</td>
            </tr>
        </table>
    </div>

</body>
</html>