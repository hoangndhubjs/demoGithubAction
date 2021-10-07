<?php
// Aside menu
return [

    'items' => [
        // Dashboard
        [
            'title' => 'Trang tổng quan',
            'root' => true,
            'icon' => 'media/svg/icons/Design/Layers.svg',
            'page' => 'dashboard/index',
            'route' => 'dashboard',
            'active_routes' => ['dashboard'],
            'new-tab' => false,
        ],
//        [
//            'title' => 'Thời gian làm việc',
//            'root' => true,
//            'icon' => 'media/svg/icons/Code/Time-schedule.svg',
//            'page' => 'office-shift/list',
//            'route' => 'office-shift.list',
//            'active_routes' => ['office-shift.list'],
//            'new-tab' => false,
//        ],
        [
            'title' => 'Chấm công',
            'root' => true,
            'icon' => 'media/svg/icons/Communication/Clipboard-check.svg',
            'page' => '#',
            'route' => 'timesheet.attendance_monthly',
            'active_routes' => ['timesheet.attendance_monthly'],
            'new-tab' => false,
        ],
        [
            'title' => 'Làm thêm giờ',
            'root' => true,
            'icon' => 'media/svg/icons/Home/Timer.svg',
            'page' => 'overtime-request/list',
            'route' => 'overtime_request.list',
            'active_routes' => ['overtime_request.list'],
            'new-tab' => false,
        ],


        [
            'title' => 'Lịch',
            'root' => true,
            'icon' => 'media/svg/icons/Layout/Layout-grid.svg',
            'page' => 'calendars/list',
            'route' => null,
            'active_routes' => ['calendars.list'],
            'new-tab' => false,
        ],
        [
            'title' => 'Tài liệu',
            'root' => true,
            'icon' => 'media/svg/icons/Files/Locked-folder.svg',
            'page' => '#',
            'route' => 'files.filemanager',
            'active_routes' => ['files.filemanager'],
            'new-tab' => false,
        ],
        [
            'title' => 'Xin nghỉ phép',
            'root' => true,
            'icon' => 'media/svg/icons/Communication/Outgoing-mail.svg',
            'page' => 'leaves/list',
            'route' => 'leaves.list',
            'active_routes' => ['leaves.list'],
            'new-tab' => false,
        ],
        [
            'title' => 'Quản lý nghỉ phép',
            'root' => true,
            'icon' => 'media/svg/icons/Communication/Outgoing-mail.svg',
            'page' => 'leaves/admin/list',
            'route' => 'leaves.admin.list',
            'active_routes' => ['leaves.admin.list'],
            'role' => 'leader',
            'new-tab' => false,
        ],
        [
            'title' => 'Đặt cơm',
            'root' => true,
            'icon' => 'media/svg/icons/Shopping/Cart3.svg',
            'page' => 'orders/meal-order',
            'route' => 'orders.meal-order',
            'active_routes' => ['orders.meal-order'],
            'new-tab' => false,
        ],
        /*[
            'title' => 'Khiếu nại',
            'root' => true,
            'icon' => 'media/svg/icons/Code/Warning-1-circle.svg',
            'page' => 'complaint/list',
            'route' => 'complaint.list',
            'active_routes' => ['complaint.list'],
            'new-tab' => false,
        ],*/
        [
            'title' => 'Bù công',
            'root' => true,
            'icon' => 'media/svg/icons/Files/Compiled-file.svg',
            'page' => 'compensations/list',
            'route' => 'compensations.list',
            'active_routes' => ['compensations.list'],
            'new-tab' => false,
        ],
        [
            'title' => 'Tạm ứng',
            'root' => true,
            'icon' => 'media/svg/icons/Shopping/Dollar.svg',
            'page' => 'payrolls.advance_money',
            'route' => 'payrolls.advance_money',
            'active_routes' => ['payrolls.advance_money'],
            'new-tab' => false,
        ],
    ]

];
