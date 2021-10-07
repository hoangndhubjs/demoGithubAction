// Class definition
var KTSelect2 = function() {
    // Private functions
    var date=new Date();
    var year=date.getFullYear();
    var month=date.getMonth();
    var payroll_init = function() {
        $("#month_filter").datepicker({
            format: "mm-yyyy",
            startView: "months",
            minViewMode: "months",
            orientation: "bottom",
            endDate: new Date(year, month, '01'),
            todayHighlight: true,
            language: window._locale,
            autoclose:true
        });
        // basic
        $('#kt_select2_status, #kt_select2_department, #kt_select2_month, #kt_select2_empolyee__').select2({
            placeholder: "Select a state"
        });
        //company
        $("#kt_select2_1").select2({
            placeholder: "Chọn công ty"
        });
        //company
        $("#kt_selectStatus").select2({
            placeholder: "Tình trạng"
        });
        $("#kt_select2_empolyee_deparment").select2({
            placeholder: "Nhan vien"
        });
        // loading data from array
        var data = [{
            id: 0,
            text: 'Enhancement'
        }];
        $('#kt_select2_5').select2({
            placeholder: "Select a value",
            data: data
        });
        $("#department_select").select2({
            placeholder: "Phòng ban",
        });
        $('#resetForm').click(function(){
            $('#payroll_list_search_form').trigger("reset");
            $('#kt_select2_1').trigger('change');
            $('#kt_select2_department').trigger('change');
            $(this).blur();
            $("#kt_select2_empolyee__").html('<option class="text-muted" value="0" selected disabled>'+__('empolyee__')+'</option>');
        });
    //    get employee id
        $("#kt_select2_1").change(function(e) {
            e.preventDefault();
            const self = $(this);
            let company_id = self.val();
            var html = '';
            $.ajax({
                type: "GET",
                url: '/salary_managements/listEmployeeCompany',
                data: {company_id:company_id},
                cache: false,
                success: function (result_data) {
                    if(result_data){
                        html += '<option value="0" selected disabled>'+__('empolyee__')+'</option>';
                        $.each(result_data, function (key,item){
                            html += '<option value="'+item['user_id']+'">';
                            html += item['first_name'] + ' ' + item['last_name'];
                            html += '</option>';
                        });
                        $("#kt_select2_empolyee__").html(html);
                    }

                }
            });
        });
    //get employee deparment
        $("#department_select").change(function(e) {
            e.preventDefault();
            const self = $(this);
            let department_id = self.val();
            var html = '';
            $.ajax({
                type: "GET",
                url: '/employee_managements/listEmployeeDepartment',
                data: {department_id:department_id},
                cache: false,
                success: function (result_data) {
                    if(result_data){
                        html += '<option value="0" selected disabled>'+__('empolyee__')+'</option>';
                        $.each(result_data, function (key,item){
                            html += '<option value="'+item['user_id']+'">';
                            html += item['first_name'] + ' ' + item['last_name'];
                            html += '</option>';
                        });

                        $("#kt_select2_empolyee_deparment").html(html);
                    }

                }
            });
        });
    }
    // Public functions
    return {
        init: function() {
            payroll_init();
        }
    };
}();

// Initialization
jQuery(document).ready(function() {
    KTSelect2.init();
});
