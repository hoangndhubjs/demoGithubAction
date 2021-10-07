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
            /*'permission' => 'dashboard',*/
            'active_routes' => ['dashboard'],
            'new-tab' => false,
        ],
        [
            'title' => 'Quản lý nhân viên',
            'root' => true,
            'icon' => 'media/svg/icons/Communication/Group.svg',
            'page' => '#',
            'route' => 'timesheet.attendance_monthly',
            'active_routes' => ['employee_managements.list','employee_managements.staff_profile_set', 'salary_managements.history_payroll', 'office-shift.list','complaint.list'],
            'new-tab' => false,
            'submenu' => [
                /*[
                    'title' => 'Bảng tổng quan nhân viên',
                    'bullet' => 'dot',
                    'route' => 'employee_managements.list',
                    'active_routes' => ['employee_managements.list'],
                ],*/
                [
                    'title' => 'Thiết lập hồ sơ nhân viên',
                    'bullet' => 'dot',
                    'route' => 'employee_managements.staff_profile_set',
                    'active_routes' => ['employee_managements.staff_profile_set'],
                ],
                [
                    'title' => 'Thời gian làm việc',
                    'bullet' => 'dot',
                    /*'icon' => 'media/svg/icons/Code/Time-schedule.svg',*/
                    'page' => 'office-shift/list',
                    'route' => 'office-shift.list',
                    'active_routes' => ['office-shift.list'],
                ],
                /*[
                    'title' => 'Thiết lập vai trò',
                    'bullet' => 'dot',
                    'route' => 'salary_managements.history_payroll',
                    'active_routes' => ['salary_managements.history_payroll'],
                ],
                [
                    'title' => 'Thiết lập thời gian làm việc',
                    'bullet' => 'dot',
                    'route' => 'salary_managements.advance_money',
                    'active_routes' => ['salary_managements.advance_money'],
                ],*/
                /*[
                    'title' => 'Khiếu nại',
                    'bullet' => 'dot',
                    'page' => 'complaint/list',
                    'route' => 'complaint.list',
                    'active_routes' => ['complaint.list'],
                    'new-tab' => false,
                ],*/
            ]
        ],
        [
            'title' => 'Quản lý tiền lương',
            'root' => true,
            'icon' => 'media/svg/icons/Shopping/Dollar.svg',
            'page' => '#',
            'active_routes' => ['payrolls.list','payrolls.payroll','payrolls.history_payroll','payrolls.advance_money'],
            'new-tab' => false,
            'submenu' => [
                [
                    'title' => 'Tổng quan tài chính',
                    'bullet' => 'dot',
                    'route' => 'payrolls.list',
                    'active_routes' => ['payrolls.list'],
                ],
//                [
//                    'title' => 'Biên chế',
//                    'bullet' => 'dot',
//                    'route' => 'payrolls.payroll',
//                    'active_routes' => ['payrolls.payroll'],
//                ],
//                [
//                    'title' => 'Lịch sử trả lương',
//                    'bullet' => 'dot',
//                    'route' => 'payrolls.history_payroll',
//                    'active_routes' => ['payrolls.history_payroll'],
//                ],
                [
                    'title' => 'Tạm ứng',
                    'bullet' => 'dot',
                    'route' => 'payrolls.advance_money',
                    'active_routes' => ['payrolls.advance_money'],
                ],
                [
                    'title' => 'Dữ liệu chấm công',
                    'bullet' => 'dot',
                    'route' => 'payrolls.timekeeping_payroll_data',
                    'active_routes' => ['payrolls.timekeeping_payroll_data'],
                ],
                [
                    'title' => 'Dữ liệu lương',
                    'bullet' => 'dot',
                    'route' => 'payrolls.payroll-data',
                    'active_routes' => ['payrolls.payroll-data'],
                ],
            ]
        ],
        [
            'title' => 'Quản lý thời gian',
            'root' => true,
            'icon' => 'media/svg/icons/Home/Timer.svg',
            'page' => '#',
            'active_routes' => ['admin.timesheet.list', 'admin.timesheet.attendance-time', 'admin.timesheet.attendance_by_employee', 'overtime_request.list','compensations.list','timesheet.attendance_monthly', 'admin.timesheet.monthlyTimesheet' ,'admin.timesheet.workingTime'],
            'new-tab' => false,
            'route' => null,
            'submenu' => [
                [
                    'title' => 'Tổng quan thời gian làm việc',
                    'bullet' => 'dot',
                    'route' => 'admin.timesheet.workingTime',
                    'active_routes' => ['admin.timesheet.workingTime'],
                ],
                [
                    'title' => 'Chấm công',
                    'bullet' => 'dot',
                    /*'icon' => 'media/svg/icons/Communication/Clipboard-check.svg',*/
                    'page' => '#',
                    'route' => 'timesheet.attendance_monthly',
                    'active_routes' => ['timesheet.attendance_monthly'],
                ],
                [
                    'title' => 'Bảng chấm công ngày',
                    'bullet' => 'dot',
                    'route' => 'admin.timesheet.attendance-time',
                    'active_routes' => ['admin.timesheet.attendance-time'],
                ],
//                [
//                    'title' => 'Bảng chấm công tháng',
//                    'bullet' => 'dot',
//                    'route' => 'admin.timesheet.list',
//                    'active_routes' => ['admin.timesheet.list'],
//                ],
                [
                    'title' => 'Bảng chấm công tháng',
                    'bullet' => 'dot',
                    'route' => 'admin.timesheet.monthlyTimesheet',
                    'active_routes' => ['admin.timesheet.monthlyTimesheet'],
                ],
                [
                    'title' => 'Làm thêm giờ',
                    'bullet' => 'dot',
                    /*'icon' => 'media/svg/icons/Home/Timer.svg',*/
                    'page' => 'overtime-request/list',
                    'route' => 'overtime_request.list',
                    'active_routes' => ['overtime_request.list'],
                ],
                [
                    'title' => 'Bảng OT tháng',
                    'bullet' => 'dot',
                    'route' => 'admin.timesheet.overtime_month',
                    'active_routes' => ['admin.timesheet.overtime_month'],
                ],
                [
                    'title' => 'Bù công',
                    'bullet' => 'dot',
                    /*'icon' => 'media/svg/icons/Files/Compiled-file.svg',*/
                    'page' => 'compensations/list',
                    'route' => 'compensations.list',
                    'active_routes' => ['compensations.list'],
                ],
            ]
        ],
        //Tổ Chức
        [
            'title' => 'Tổ chức',
            'root' => true,
            'icon' => 'media/svg/icons/Home/organization.svg',
            'page' => '#',
            'active_routes' => ['company.index', 'location.index','department.index','designation.index','announcement.index','policy.index'],
            'new-tab' => false,
            'route' => null,
            'submenu' => [
                [
                    'title' => 'Công ty',
                    'bullet' => 'dot',
                    'page' => '#',
                    'route' => 'company.index',
                    'active_routes' => ['company.index'],
                ],
                [
                    'title' => "Chi nhánh",
                    'bullet' => 'dot',
                    /*'icon' => 'media/svg/icons/Communication/Clipboard-check.svg',*/
                    'page' => '#',
                    'route' => 'location.index',
                    'active_routes' => ['location.index'],
                ],
                [
                    'title' => 'Phòng ban',
                    'bullet' => 'dot',
                    'route' => 'department.index',
                    'active_routes' => ['department.index'],
                ],
                [
                    'title' => 'Chức vụ',
                    'bullet' => 'dot',
                    'route' => 'designation.index',
                    'active_routes' => ['designation.index'],
                ],
                [
                    'title' => 'Thông báo',
                    'bullet' => 'dot',
                    /*'icon' => 'media/svg/icons/Home/Timer.svg',*/
                    // 'page' => 'overtime-request/list',
                    'route' => 'announcement.index',
                    'active_routes' => ['announcement.index'],
                ],
                [
                    'title' => 'Chính sách',
                    'bullet' => 'dot',
                    /*'icon' => 'media/svg/icons/Files/Compiled-file.svg',*/
                    // 'page' => 'compensations/list',
                    'route' => 'policy.index',
                    'active_routes' => ['policy.index'],
                ],
            ],
        ],
        //End Tổ Chức
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
            'title' => 'Quản lý nghỉ phép',
            'root' => true,
            'icon' => 'media/svg/icons/Communication/Outgoing-mail.svg',
            'page' => 'leaves/admin/list',
            'route' => 'leaves.admin.list',
            'active_routes' => ['leaves.admin.list'],
            'new-tab' => false,
        ],
        [
            'title' => 'Đặt cơm',
            'icon' => 'media/svg/icons/Shopping/Cart3.svg',
            'bullet' => 'dot',
            'active_routes' => ['orders.menu-dishes','orders.user-order-rice','orders.user-order-rice-month'],
            'root' => true,
            'submenu' => [
                [
                    'title' => 'Danh sách thực đơn',
                    'route' => 'orders.menu-dishes',
                    'active_routes' => ['orders.menu-dishes'],
                ],
                [
                    'title' => 'Danh sách theo ngày',
                    'route' => 'orders.user-order-rice',
                    'active_routes' => ['orders.user-order-rice'],
                ],
                [
                    'title' => 'Danh sách theo tháng',
                    'route' => 'orders.user-order-rice-month',
                    'active_routes' => ['orders.user-order-rice-month'],
                ],
            ]
        ],
        [
            'title' => 'Quản lý tài sản',
            'icon' => 'media/svg/icons/Shopping/Box3.svg',
            'bullet' => 'dot',
            'active_routes' => ['admin.asset.list-store','admin.asset.list_asset','admin.asset.category'],
            'root' => true,
            'submenu' => [
                [
                    'title' => 'Danh mục kho',
                    'route' => 'admin.asset.category',
                    'active_routes' => ['admin.asset.category'],
                ],
                [
                    'title' => 'Danh sách tài sản',
                    'route' => 'admin.asset.list_asset',
                    'active_routes' => ['admin.asset.list_asset'],
                ],
                [
                    'title' => 'Kho',
                    'route' => 'admin.asset.list-store',
                    'active_routes' => ['admin.asset.list-store'],
                ],
            ]
        ]
    ]

];
