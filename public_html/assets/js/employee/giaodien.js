function showContent(section) {
    const contentDiv = document.getElementById('content');
    let content = '';

    switch (section) {
        case 'info':
            content = '<h3>Thông Tin Nhân Viên</h3><p>Họ tên: Nguyễn Văn A</p><p>Mã Nhân Viên: NV001</p><p>Phòng Ban: Kinh Doanh</p>';
            break;
        case 'kpi':
            content = '<h3>KPI</h3><p>Thông tin KPI của nhân viên sẽ hiển thị ở đây.</p>';
            break;
        case 'salary':
            content = '<h3>Lương Thưởng</h3><p>Thông tin lương thưởng sẽ hiển thị ở đây.</p>';
            break;
        case 'attendance':
            content = '<h3>Chấm Công</h3><p>Thông tin chấm công sẽ hiển thị ở đây.</p>';
            break;
        case 'leave':
            content = '<h3>Xin Nghỉ Phép</h3><p>Thông tin xin nghỉ phép sẽ hiển thị ở đây.</p>';
            break;
        default:
            content = '<h3>Chọn một mục để xem thông tin</h3>';
            break;
    }

    contentDiv.innerHTML = content;
}
