<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Classes\PaginateConfig;
use App\Traits\DatatableResponseable;
use App\Models\Company;
use App\Models\ContractType;
use App\Models\QualificationEducationLevel;
use App\Models\QualificationLanguage;
use App\Models\QualificationSkill;
use App\Models\DocumentType;
use App\Models\AwardType;
use App\Models\EthnicityType;
use App\Models\LeaveType;
use App\Models\WarningType;
use App\Models\ExpenseType;
use App\Models\IncomeCategories;
use App\Models\Currency;
use App\Models\CompanyType;
use App\Models\SecurityLevel;
use App\Models\TerminationType;
use App\Models\EmployeeExitType;
use App\Models\TravelArrangementType;
use App\Models\JobType;
use App\Models\JobCategory;

class ConstantsController extends Controller
{
    use DatatableResponseable;

    public function __construct()
    {
        View::share('adminSetting', true);
    }

    //contract type
    public function contractType() {
        $page_title = __('xin_e_details_contract_type');

        return view('admin.constants.contract_type', compact('page_title'));
    }

    public function listContractType(Request $request) {
        $paginateConfig = PaginateConfig::fromDatatable($request);

        $listContractType = $this->getContractType($paginateConfig);

        return $this->makeDatatableResponse($listContractType, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    public function getContractType($paginateConfig)
    {
        $getContractType = ContractType::orderBy('contract_type_id', 'desc');

        $query = $getContractType->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());

        return $query;
    }

    public function addContractType(Request $request)
    {
        $name = $request->contract_type;
        $query = ContractType::create(array('name' => $name));

        if ($query) {
            return $this->responseSuccess(__('xin_success_contract_type_added'));
        } else {
            return $this->responseError(__('update_error'));
        }
    }

    public function showContractType(Request $request) {
        $id = $request->id;
        $contract_type = null;
        if ($id) {
            $contract_type = ContractType::find($id);
        }
        return view('admin.constants.modal.modal_edit_contract_type', compact('contract_type'));
    }

    public function updateContractType(Request $request) {
        $id = $request->id;
        $query = ContractType::find($id)->update(['name' => $request->contract_type]);
       
        if ($query) {
            return $this->responseSuccess(__('xin_success_contract_type_updated'));
        } else {
            return $this->responseError(__('update_error'));
        }
    }

    public function deleteContractType(Request $request)
    {
        if (!$id = $request->id) {
            return $this->responseError(__("nothing_to_delete"));
        }
        if (ContractType::where('contract_type_id',$id)->delete()) {
            return $this->responseSuccess(__("xin_success_contract_type_deleted"));
        }
        return $this->responseError(__("delete_fail"));
    }
    //contract end
    
    // qualificationEduLevel
    public function qualificationEduLevel() {
        $page_title = __('xin_e_details_qualification') . " - " . __('xin_e_details_edu_level');

        return view('admin.constants.sub_qualification.education_level', compact('page_title'));
    }

    public function listQualificationEduLevel(Request $request) {
        $paginateConfig = PaginateConfig::fromDatatable($request);

        $listEduLevel = $this->getQualificationEduLevel($paginateConfig);

        return $this->makeDatatableResponse($listEduLevel, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    public function getQualificationEduLevel($paginateConfig)
    {
        $getEduLevel = QualificationEducationLevel::orderBy('education_level_id', 'desc');

        $query = $getEduLevel->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());

        return $query;
    }

    public function addQualificationEduLevel(Request $request)
    {
        $name = $request->edu_level;
        $query = QualificationEducationLevel::create(array('name' => $name));

        if ($query) {
            return $this->responseSuccess(__('xin_success_education_level_added'));
        } else {
            return $this->responseError(__('update_error'));
        }
    }

    public function showQualificationEduLevel(Request $request) {
        $id = $request->id;
        $edu_level = null;
        if ($id) {
            $edu_level = QualificationEducationLevel::find($id);
        }
        return view('admin.constants.modal.modal_edit_qualification_edu_level', compact('edu_level'));
    }

    public function updateQualificationEduLevel(Request $request) {
        $id = $request->id;
        $query = QualificationEducationLevel::find($id)->update(['name' => $request->edu_level]);
       
        if ($query) {
            return $this->responseSuccess(__('xin_success_education_level_updated'));
        } else {
            return $this->responseError(__('update_error'));
        }
    }

    public function deleteQualificationEduLevel(Request $request)
    {
        if (!$id = $request->id) {
            return $this->responseError(__("nothing_to_delete"));
        }
        if (QualificationEducationLevel::where('education_level_id',$id)->delete()) {
            return $this->responseSuccess(__("xin_success_education_level_deleted"));
        }
        return $this->responseError(__("delete_fail"));
    }
    // end qualificationEduLevel

    // qualification language
    public function qualificationLanguage() {
        $page_title = __('xin_e_details_qualification') . " - " . __('xin_e_details_language');

        return view('admin.constants.sub_qualification.language', compact('page_title'));
    }

    public function listQualificationLanguage(Request $request) {
        $paginateConfig = PaginateConfig::fromDatatable($request);

        $listLanguage = $this->getQualificationLanguage($paginateConfig);

        return $this->makeDatatableResponse($listLanguage, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    public function getQualificationLanguage($paginateConfig)
    {
        $getLanguage = QualificationLanguage::orderBy('language_id', 'desc');

        $query = $getLanguage->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());

        return $query;
    }

    public function addQualificationLanguage(Request $request)
    {
        $name = $request->language;
        $query = QualificationLanguage::create(array('name' => $name));

        if ($query) {
            return $this->responseSuccess(__('xin_success_education_language_added'));
        } else {
            return $this->responseError(__('update_error'));
        }
    }

    public function showQualificationLanguage(Request $request) {
        $id = $request->id;
        $language = null;
        if ($id) {
            $language = QualificationLanguage::find($id);
        }
        return view('admin.constants.modal.modal_edit_qualification_language', compact('language'));
    }

    public function updateQualificationLanguage(Request $request) {
        $id = $request->id;
        $query = QualificationLanguage::find($id)->update(['name' => $request->language]);
       
        if ($query) {
            return $this->responseSuccess(__('xin_success_lang_updated'));
        } else {
            return $this->responseError(__('update_error'));
        }
    }

    public function deleteQualificationLanguage(Request $request)
    {
        if (!$id = $request->id) {
            return $this->responseError(__("nothing_to_delete"));
        }
        if (QualificationLanguage::where('language_id',$id)->delete()) {
            return $this->responseSuccess(__("xin_success_qualification_lang_deleted"));
        }
        return $this->responseError(__("delete_fail"));
    }
    // end qualification language
    
    // qualification skill
    public function qualificationSkill() {
        $page_title = __('xin_e_details_qualification') . " - " . __('xin_skill');

        return view('admin.constants.sub_qualification.skill', compact('page_title'));
    }

    public function listQualificationSkill(Request $request) {
        $paginateConfig = PaginateConfig::fromDatatable($request);

        $listSkill = $this->getQualificationSkill($paginateConfig);

        return $this->makeDatatableResponse($listSkill, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    public function getQualificationSkill($paginateConfig)
    {
        $getSkill = QualificationSkill::orderBy('skill_id', 'desc');

        $query = $getSkill->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());

        return $query;
    }

    public function addQualificationSkill(Request $request)
    {
        $name = $request->skill;
        $query = QualificationSkill::create(array('name' => $name));

        if ($query) {
            return $this->responseSuccess(__('xin_success_education_skill_added'));
        } else {
            return $this->responseError(__('update_error'));
        }
    }

    public function showQualificationSkill(Request $request) {
        $id = $request->id;
        $skill = null;
        if ($id) {
            $skill = QualificationSkill::find($id);
        }
        return view('admin.constants.modal.modal_edit_qualification_skill', compact('skill'));
    }

    public function updateQualificationSkill(Request $request) {
        $id = $request->id;
        $query = QualificationSkill::find($id)->update(['name' => $request->skill]);
       
        if ($query) {
            return $this->responseSuccess(__("xin_success_qualification_skill_updated"));
        } else {
            return $this->responseError(__('update_error'));
        }
    }

    public function deleteQualificationSkill(Request $request)
    {
        if (!$id = $request->id) {
            return $this->responseError(__("nothing_to_delete"));
        }
        if (QualificationSkill::where('skill_id',$id)->delete()) {
            return $this->responseSuccess(__("xin_success_qualification_skill_deleted"));
        }
        return $this->responseError(__("delete_fail"));
    }
    // end qualification skill

    //document type
    public function documentType() {
        $page_title = __('xin_e_details_dtype');

        return view('admin.constants.document_type', compact('page_title'));
    }

    public function listDocumentType(Request $request) {
        $paginateConfig = PaginateConfig::fromDatatable($request);

        $listDocumentType = $this->getDocumentType($paginateConfig);

        return $this->makeDatatableResponse($listDocumentType, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    public function getDocumentType($paginateConfig)
    {
        $getDocumentType = DocumentType::orderBy('document_type_id', 'desc');

        $query = $getDocumentType->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());

        return $query;
    }

    public function addDocumentType(Request $request)
    {
        $document_type = $request->document_type;
        $query = DocumentType::create(array('document_type' => $document_type));

        if ($query) {
            return $this->responseSuccess(__('xin_success_document_type_added'));
        } else {
            return $this->responseError(__('update_error'));
        }
    }

    public function showDocumentType(Request $request) {
        $id = $request->id;
        $document_type = null;
        if ($id) {
            $document_type = DocumentType::find($id);
        }
        return view('admin.constants.modal.modal_edit_document_type', compact('document_type'));
    }

    public function updateDocumentType(Request $request) {
        $id = $request->id;
        $query = DocumentType::find($id)->update(['document_type' => $request->document_type]);
       
        if ($query) {
            return $this->responseSuccess(__("xin_success_document_type_updated"));
        } else {
            return $this->responseError(__('update_error'));
        }
    }

    public function deleteDocumentType(Request $request)
    {
        if (!$id = $request->id) {
            return $this->responseError(__("nothing_to_delete"));
        }
        if (DocumentType::where('document_type_id',$id)->delete()) {
            return $this->responseSuccess(__("xin_success_document_type_deleted"));
        }
        return $this->responseError(__("delete_fail"));
    }
    // end document type

    // award type
    public function awardType() {
        $page_title = __('xin_award_type');

        return view('admin.constants.award_type', compact('page_title'));
    }

    public function listAwardType(Request $request) {
        $paginateConfig = PaginateConfig::fromDatatable($request);

        $listAwardType = $this->getAwardType($paginateConfig);

        return $this->makeDatatableResponse($listAwardType, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    public function getAwardType($paginateConfig)
    {
        $getAwardType = AwardType::orderBy('award_type_id', 'desc');

        $query = $getAwardType->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());

        return $query;
    }

    public function addAwardType(Request $request)
    {
        $award_type = $request->award_type;
        $query = AwardType::create(array('award_type' => $award_type));

        if ($query) {
            return $this->responseSuccess(__('xin_success_award_type_added'));
        } else {
            return $this->responseError(__('update_error'));
        }
    }

    public function showAwardType(Request $request) {
        $id = $request->id;
        $award_type = null;
        if ($id) {
            $award_type = AwardType::find($id);
        }
        return view('admin.constants.modal.modal_edit_award_type', compact('award_type'));
    }

    public function updateAwardType(Request $request) {
        $id = $request->id;
        $query = AwardType::find($id)->update(['award_type' => $request->award_type]);
       
        if ($query) {
            return $this->responseSuccess(__("xin_success_award_type_updated"));
        } else {
            return $this->responseError(__('update_error'));
        }
    }

    public function deleteAwardType(Request $request)
    {
        if (!$id = $request->id) {
            return $this->responseError(__("nothing_to_delete"));
        }
        if (AwardType::where('award_type_id',$id)->delete()) {
            return $this->responseSuccess(__("xin_success_award_type_deleted"));
        }
        return $this->responseError(__("delete_fail"));
    }
    // end award type

    // ethnicity type
    public function ethnicityType()
    {
        $page_title = __('xin_ethnicity_type_title');

        return view('admin.constants.ethnicity_type', compact('page_title'));
    }

    public function listEthnicityType(Request $request) {
        $paginateConfig = PaginateConfig::fromDatatable($request);

        $listEthnicityType = $this->getEthnicityType($paginateConfig);

        return $this->makeDatatableResponse($listEthnicityType, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    public function getEthnicityType($paginateConfig)
    {
        $getEthnicityType = EthnicityType::orderBy('ethnicity_type_id', 'desc');

        $query = $getEthnicityType->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());

        return $query;
    }

    public function addEthnicityType(Request $request)
    {
        $ethnicity_type = $request->ethnicity_type;
        $query = EthnicityType::create(array('type' => $ethnicity_type));

        if ($query) {
            return $this->responseSuccess(__('xin_ethnicity_type_success_added'));
        } else {
            return $this->responseError(__('update_error'));
        }
    }

    public function showEthnicityType(Request $request) {
        $id = $request->id;
        $ethnicity_type = null;
        if ($id) {
            $ethnicity_type = EthnicityType::find($id);
        }
        return view('admin.constants.modal.modal_edit_ethnicity_type', compact('ethnicity_type'));
    }

    public function updateEthnicityType(Request $request) {
        $id = $request->id;
        $query = EthnicityType::find($id)->update(['type' => $request->ethnicity_type]);
       
        if ($query) {
            return $this->responseSuccess(__("xin_ethnicity_type_success_updated"));
        } else {
            return $this->responseError(__('update_error'));
        }
    }

    public function deleteEthnicityType(Request $request)
    {
        if (!$id = $request->id) {
            return $this->responseError(__("nothing_to_delete"));
        }
        if (EthnicityType::where('ethnicity_type_id',$id)->delete()) {
            return $this->responseSuccess(__("xin_ethnicity_type_success_delatted"));
        }
        return $this->responseError(__("delete_fail"));
    }
    // end ethnicity type


    // leave type
    public function leaveType()
    {
        $page_title = __('xin_leave_type');

        return view('admin.constants.leave_type', compact('page_title'));
    }

    public function listLeaveType(Request $request) {
        $paginateConfig = PaginateConfig::fromDatatable($request);

        $listLeaveType = $this->getLeaveType($paginateConfig);

        return $this->makeDatatableResponse($listLeaveType, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    public function getLeaveType($paginateConfig)
    {
        $getLeaveType = LeaveType::orderBy('leave_type_id', 'desc');

        $query = $getLeaveType->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());

        return $query;
    }

    public function addLeaveType(Request $request)
    {
        $type_name = $request->type_name;
        $dPY = $request->days_per_year;
        $query = LeaveType::create(array('type_name' => $type_name, 'days_per_year' => $dPY));

        if ($query) {
            return $this->responseSuccess(__('xin_success_leave_type_added'));
        } else {
            return $this->responseError(__('update_error'));
        }
    }

    public function showLeaveType(Request $request) {
        $id = $request->id;
        $leave_type = null;
        if ($id) {
            $leave_type = LeaveType::find($id);
        }
        return view('admin.constants.modal.modal_edit_leave_type', compact('leave_type'));
    }

    public function updateLeaveType(Request $request) {
        $id = $request->id;
        $query = LeaveType::find($id)->update(['type_name' => $request->type_name, 'days_per_year' => $request->days_per_year]);
       
        if ($query) {
            return $this->responseSuccess(__("xin_success_leave_type_updated"));
        } else {
            return $this->responseError(__('update_error'));
        }
    }

    public function deleteLeaveType(Request $request)
    {
        if (!$id = $request->id) {
            return $this->responseError(__("nothing_to_delete"));
        }
        if (LeaveType::where('leave_type_id',$id)->delete()) {
            return $this->responseSuccess(__("xin_success_leave_type_deleted"));
        }
        return $this->responseError(__("delete_fail"));
    }
    // end leave type

    // warning type
    public function warningType()
    {
        $page_title = __('xin_warning_type');

        return view('admin.constants.warning_type', compact('page_title'));
    }

    public function listWarningType(Request $request) {
        $paginateConfig = PaginateConfig::fromDatatable($request);

        $listWarningType = $this->getWarningType($paginateConfig);

        return $this->makeDatatableResponse($listWarningType, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    public function getWarningType($paginateConfig)
    {
        $getWarningType = WarningType::orderBy('warning_type_id', 'desc');

        $query = $getWarningType->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());

        return $query;
    }

    public function addWarningType(Request $request)
    {
        $type = $request->type;
        $query = WarningType::create(array('type' => $type));

        if ($query) {
            return $this->responseSuccess(__('xin_success_warning_type_added'));
        } else {
            return $this->responseError(__('update_error'));
        }
    }

    public function showWarningType(Request $request) {
        $id = $request->id;
        $warning_type = null;
        if ($id) {
            $warning_type = WarningType::find($id);
        }
        return view('admin.constants.modal.modal_edit_warning_type', compact('warning_type'));
    }

    public function updateWarningType(Request $request) {
        $id = $request->id;
        $query = WarningType::find($id)->update(['type' => $request->type]);
       
        if ($query) {
            return $this->responseSuccess(__("xin_success_warning_type_updated"));
        } else {
            return $this->responseError(__('update_error'));
        }
    }

    public function deleteWarningType(Request $request)
    {
        if (!$id = $request->id) {
            return $this->responseError(__("nothing_to_delete"));
        }
        if (WarningType::where('warning_type_id',$id)->delete()) {
            return $this->responseSuccess(__("xin_success_warning_type_deleted"));
        }
        return $this->responseError(__("delete_fail"));
    }
    // end warning type

    // expense type
    public function expenseType()
    {
        $page_title = __('xin_expense_type');
        $company = Company::get();
        return view('admin.constants.expense_type', compact('page_title', 'company'));
    }

    public function listExpenseType(Request $request) {
        $paginateConfig = PaginateConfig::fromDatatable($request);

        $listExpenseType = $this->getExpenseType($paginateConfig);

        return $this->makeDatatableResponse($listExpenseType, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    public function getExpenseType($paginateConfig)
    {
        $getExpenseType = ExpenseType::orderBy('expense_type_id', 'desc');

        $query = $getExpenseType->with(['company'])->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());

        return $query;
    }

    public function addExpenseType(Request $request)
    {
        $name = $request->expense_type;
        $company_id = $request->company_id;
        $query = ExpenseType::create(array('name' => $name, 'company_id' => $company_id));

        if ($query) {
            return $this->responseSuccess(__('xin_success_expense_type_added'));
        } else {
            return $this->responseError(__('update_error'));
        }
    }

    public function showExpenseType(Request $request) {
        $id = $request->id;
        $expense_type = null;
        if ($id) {
            $expense_type = ExpenseType::find($id);
            $company = Company::get();
        }
        return view('admin.constants.modal.modal_edit_expense_type', compact('expense_type', 'company'));
    }

    public function updateExpenseType(Request $request) {
        $id = $request->id;
        $query = ExpenseType::find($id)->update(['name' => $request->expense_type, 'company_id' => $request->company_id]);
       
        if ($query) {
            return $this->responseSuccess(__("xin_success_expense_type_updated"));
        } else {
            return $this->responseError(__('update_error'));
        }
    }

    public function deleteExpenseType(Request $request)
    {
        if (!$id = $request->id) {
            return $this->responseError(__("nothing_to_delete"));
        }
        if (ExpenseType::where('expense_type_id',$id)->delete()) {
            return $this->responseSuccess(__("xin_success_expense_type_deleted"));
        }
        return $this->responseError(__("delete_fail"));
    }
    //end expence type

    //income type
    public function incomeType()
    {
        $page_title = __('xin_income_type');

        return view('admin.constants.income_type', compact('page_title'));
    }

    public function listIncomeType(Request $request) {
        $paginateConfig = PaginateConfig::fromDatatable($request);

        $listIncomeType = $this->getIncomeType($paginateConfig);

        return $this->makeDatatableResponse($listIncomeType, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    public function getIncomeType($paginateConfig)
    {
        $getIncomeType = IncomeCategories::orderBy('category_id', 'desc');

        $query = $getIncomeType->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());

        return $query;
    }

    public function addIncomeType(Request $request)
    {
        $name = $request->income_type;
        $query = IncomeCategories::create(array('name' => $name));

        if ($query) {
            return $this->responseSuccess(__('xin_income_type_success_added'));
        } else {
            return $this->responseError(__('update_error'));
        }
    }

    public function showIncomeType(Request $request) {
        $id = $request->id;
        $income_type = null;
        if ($id) {
            $income_type = IncomeCategories::find($id);
        }
        return view('admin.constants.modal.modal_edit_income_type', compact('income_type'));
    }

    public function updateIncomeType(Request $request) {
        $id = $request->id;
        $query = IncomeCategories::find($id)->update(['name' => $request->income_type]);
       
        if ($query) {
            return $this->responseSuccess(__("xin_income_type_success_updated"));
        } else {
            return $this->responseError(__('update_error'));
        }
    }

    public function deleteIncomeType(Request $request)
    {
        if (!$id = $request->id) {
            return $this->responseError(__("nothing_to_delete"));
        }
        if (IncomeCategories::where('category_id',$id)->delete()) {
            return $this->responseSuccess(__("xin_income_type_success_delatted"));
        }
        return $this->responseError(__("delete_fail"));
    }
    //end income type

    //currency type
    public function currencyType()
    {
        $page_title = __('xin_currency_type');

        return view('admin.constants.currency_type', compact('page_title'));
    }

    public function listCurrencyType(Request $request) {
        $paginateConfig = PaginateConfig::fromDatatable($request);

        $listCurrencyType = $this->getCurrencyType($paginateConfig);

        return $this->makeDatatableResponse($listCurrencyType, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    public function getCurrencyType($paginateConfig)
    {
        $getCurrencyType = Currency::orderBy('currency_id', 'desc');

        $query = $getCurrencyType->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());

        return $query;
    }

    public function addCurrencyType(Request $request)
    {
        $name = $request->name;
        $code = $request->code;
        $symbol = $request->symbol;
        $query = Currency::create(array('name' => $name, 'code' => $code, 'symbol' => $symbol));

        if ($query) {
            return $this->responseSuccess(__('xin_success_currency_type_added'));
        } else {
            return $this->responseError(__('update_error'));
        }
    }

    public function showCurrencyType(Request $request) {
        $id = $request->id;
        $currency_type = null;
        if ($id) {
            $currency_type = Currency::find($id);
        }
        return view('admin.constants.modal.modal_edit_currency_type', compact('currency_type'));
    }

    public function updateCurrencyType(Request $request) {
        $id = $request->id;
        $query = Currency::find($id)->update(['name' => $request->name, 'code' => $request->code, 'symbol' => $request->symbol]);
       
        if ($query) {
            return $this->responseSuccess(__("xin_success_currency_type_updated"));
        } else {
            return $this->responseError(__('update_error'));
        }
    }

    public function deleteCurrencyType(Request $request)
    {
        if (!$id = $request->id) {
            return $this->responseError(__("nothing_to_delete"));
        }
        if (Currency::where('currency_id',$id)->delete()) {
            return $this->responseSuccess(__("xin_success_currency_type_deleted"));
        }
        return $this->responseError(__("delete_fail"));
    }
    //end currency type

    //company type
    public function companyType()
    {
        $page_title = __('xin_company_type');

        return view('admin.constants.company_type', compact('page_title'));
    }

    public function listCompanyType(Request $request) {
        $paginateConfig = PaginateConfig::fromDatatable($request);

        $listCompanyType = $this->getCompanyType($paginateConfig);

        return $this->makeDatatableResponse($listCompanyType, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    public function getCompanyType($paginateConfig)
    {
        $getCompanyType = CompanyType::orderBy('type_id', 'desc');

        $query = $getCompanyType->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());

        return $query;
    }

    public function addCompanyType(Request $request)
    {
        $name = $request->company_type;
        $query = CompanyType::create(array('name' => $name));

        if ($query) {
            return $this->responseSuccess(__('xin_company_type_added'));
        } else {
            return $this->responseError(__('update_error'));
        }
    }

    public function showCompanyType(Request $request) {
        $id = $request->id;
        $company_type = null;
        if ($id) {
            $company_type = CompanyType::find($id);
        }
        return view('admin.constants.modal.modal_edit_company_type', compact('company_type'));
    }

    public function updateCompanyType(Request $request) {
        $id = $request->id;
        $query = CompanyType::find($id)->update(['name' => $request->company_type]);
       
        if ($query) {
            return $this->responseSuccess(__("xin_company_type_updated"));
        } else {
            return $this->responseError(__('update_error'));
        }
    }

    public function deleteCompanyType(Request $request)
    {
        if (!$id = $request->id) {
            return $this->responseError(__("nothing_to_delete"));
        }
        if (CompanyType::where('type_id',$id)->delete()) {
            return $this->responseSuccess(__("xin_company_type_deleted"));
        }
        return $this->responseError(__("delete_fail"));
    }
    //end company type

    //security type
    public function securityType()
    {
        $page_title = __('xin_security_level');

        return view('admin.constants.security_type', compact('page_title'));
    }

    public function listSecurityType(Request $request) {
        $paginateConfig = PaginateConfig::fromDatatable($request);

        $listSecurityType = $this->getSecurityType($paginateConfig);

        return $this->makeDatatableResponse($listSecurityType, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    public function getSecurityType($paginateConfig)
    {
        $getSecurityType = SecurityLevel::orderBy('type_id', 'desc');

        $query = $getSecurityType->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());

        return $query;
    }

    public function addSecurityType(Request $request)
    {
        $name = $request->security_type;
        $query = SecurityLevel::create(array('name' => $name));

        if ($query) {
            return $this->responseSuccess(__('xin_security_level_added'));
        } else {
            return $this->responseError(__('update_error'));
        }
    }

    public function showSecurityType(Request $request) {
        $id = $request->id;
        $security_type = null;
        if ($id) {
            $security_type = SecurityLevel::find($id);
        }
        return view('admin.constants.modal.modal_edit_security_type', compact('security_type'));
    }

    public function updateSecurityType(Request $request) {
        $id = $request->id;
        $query = SecurityLevel::find($id)->update(['name' => $request->security_type]);
       
        if ($query) {
            return $this->responseSuccess(__("xin_security_level_updated"));
        } else {
            return $this->responseError(__('update_error'));
        }
    }

    public function deleteSecurityType(Request $request)
    {
        if (!$id = $request->id) {
            return $this->responseError(__("nothing_to_delete"));
        }
        if (SecurityLevel::where('type_id',$id)->delete()) {
            return $this->responseSuccess(__("xin_security_level_delatted"));
        }
        return $this->responseError(__("delete_fail"));
    }
    //end security type

    //termination type
    public function terminationType()
    {
        $page_title = __('xin_termination_type');

        return view('admin.constants.termination_type', compact('page_title'));
    }

    public function listTerminationType(Request $request) {
        $paginateConfig = PaginateConfig::fromDatatable($request);

        $listTerminationType = $this->getTerminationType($paginateConfig);

        return $this->makeDatatableResponse($listTerminationType, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    public function getTerminationType($paginateConfig)
    {
        $getTerminationType = TerminationType::orderBy('termination_type_id', 'desc');

        $query = $getTerminationType->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());

        return $query;
    }

    public function addTerminationType(Request $request)
    {
        $type = $request->termination_type;
        $query = TerminationType::create(array('type' => $type));

        if ($query) {
            return $this->responseSuccess(__('xin_success_termination_type_added'));
        } else {
            return $this->responseError(__('update_error'));
        }
    }

    public function showTerminationType(Request $request) {
        $id = $request->id;
        $termination_type = null;
        if ($id) {
            $termination_type = TerminationType::find($id);
        }
        return view('admin.constants.modal.modal_edit_termination_type', compact('termination_type'));
    }

    public function updateTerminationType(Request $request) {
        $id = $request->id;
        $query = TerminationType::find($id)->update(['type' => $request->termination_type]);
       
        if ($query) {
            return $this->responseSuccess(__("xin_success_termination_type_updated"));
        } else {
            return $this->responseError(__('update_error'));
        }
    }

    public function deleteTerminationType(Request $request)
    {
        if (!$id = $request->id) {
            return $this->responseError(__("nothing_to_delete"));
        }
        if (TerminationType::where('termination_type_id',$id)->delete()) {
            return $this->responseSuccess(__("xin_success_termination_type_deleted"));
        }
        return $this->responseError(__("delete_fail"));
    }
    //end termination type

    //exit type
    public function exitType()
    {
        $page_title = __('Các hình thức nghỉ');

        return view('admin.constants.exit_type', compact('page_title'));
    }

    public function listExitType(Request $request) {
        $paginateConfig = PaginateConfig::fromDatatable($request);

        $listExitType = $this->getExitType($paginateConfig);

        return $this->makeDatatableResponse($listExitType, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    public function getExitType($paginateConfig)
    {
        $getExitType = EmployeeExitType::orderBy('exit_type_id', 'desc');

        $query = $getExitType->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());

        return $query;
    }

    public function addExitType(Request $request)
    {
        $type = $request->exit_type;
        $query = EmployeeExitType::create(array('type' => $type));

        if ($query) {
            return $this->responseSuccess(__('Loại xin ra ngoài được thêm vào'));
        } else {
            return $this->responseError(__('update_error'));
        }
    }

    public function showExitType(Request $request) {
        $id = $request->id;
        $exit_type = null;
        if ($id) {
            $exit_type = EmployeeExitType::find($id);
        }
        return view('admin.constants.modal.modal_edit_exit_type', compact('exit_type'));
    }

    public function updateExitType(Request $request) {
        $id = $request->id;
        $query = EmployeeExitType::find($id)->update(['type' => $request->exit_type]);
       
        if ($query) {
            return $this->responseSuccess(__("Loại xin ra ngoài được cập nhập"));
        } else {
            return $this->responseError(__('update_error'));
        }
    }

    public function deleteExitType(Request $request)
    {
        if (!$id = $request->id) {
            return $this->responseError(__("nothing_to_delete"));
        }
        if (EmployeeExitType::where('exit_type_id',$id)->delete()) {
            return $this->responseSuccess(__("delete_success"));
        }
        return $this->responseError(__("delete_fail"));
    }
    //end exit type

    //arrangement type
    public function arrangementType()
    {
        $page_title = __('xin_travel_arrangement_type');

        return view('admin.constants.arrangement_type', compact('page_title'));
    }

    public function listArrangementType(Request $request) {
        $paginateConfig = PaginateConfig::fromDatatable($request);

        $listArrangementType = $this->getArrangementType($paginateConfig);

        return $this->makeDatatableResponse($listArrangementType, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    public function getArrangementType($paginateConfig)
    {
        $getArrangementType = TravelArrangementType::orderBy('arrangement_type_id', 'desc');

        $query = $getArrangementType->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());

        return $query;
    }

    public function addArrangementType(Request $request)
    {
        $type = $request->arrangement_type;
        $query = TravelArrangementType::create(array('type' => $type));

        if ($query) {
            return $this->responseSuccess(__('xin_success_travel_arrangment_type_added'));
        } else {
            return $this->responseError(__('update_error'));
        }
    }

    public function showArrangementType(Request $request) {
        $id = $request->id;
        $arrangement_type = null;
        if ($id) {
            $arrangement_type = TravelArrangementType::find($id);
        }
        return view('admin.constants.modal.modal_edit_arrangement_type', compact('arrangement_type'));
    }

    public function updateArrangementType(Request $request) {
        $id = $request->id;
        $query = TravelArrangementType::find($id)->update(['type' => $request->arrangement_type]);
       
        if ($query) {
            return $this->responseSuccess(__("xin_success_travel_arrtype_updated"));
        } else {
            return $this->responseError(__('update_error'));
        }
    }

    public function deleteArrangementType(Request $request)
    {
        if (!$id = $request->id) {
            return $this->responseError(__("nothing_to_delete"));
        }
        if (TravelArrangementType::where('arrangement_type_id',$id)->delete()) {
            return $this->responseSuccess(__("xin_success_travel_arrtype_deleted"));
        }
        return $this->responseError(__("delete_fail"));
    }
    //end arrangement type

    //job type
    public function jobType()
    {
        $page_title = __('xin_job_type');

        return view('admin.constants.job_type', compact('page_title'));
    }

    public function listJobType(Request $request) {
        $paginateConfig = PaginateConfig::fromDatatable($request);

        $listJobType = $this->getJobType($paginateConfig);

        return $this->makeDatatableResponse($listJobType, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    public function getJobType($paginateConfig)
    {
        $getJobType = JobType::orderBy('job_type_id', 'desc');

        $query = $getJobType->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());

        return $query;
    }

    public function addJobType(Request $request)
    {
        $type = $request->job_type;
        $jurl = $this->random_string(40);
        $company_id = 1;
        $query = JobType::create(array('type' => $type, 'type_url' => $jurl, 'company_id' => $company_id));

        if ($query) {
            return $this->responseSuccess(__('xin_success_job_type_added'));
        } else {
            return $this->responseError(__('update_error'));
        }
    }

    public function showJobType(Request $request) {
        $id = $request->id;
        $job_type = null;
        if ($id) {
            $job_type = JobType::find($id);
        }
        return view('admin.constants.modal.modal_edit_job_type', compact('job_type'));
    }

    public function updateJobType(Request $request) {
        $id = $request->id;
        $query = JobType::find($id)->update(['type' => $request->job_type]);
       
        if ($query) {
            return $this->responseSuccess(__("xin_success_job_type_updated"));
        } else {
            return $this->responseError(__('update_error'));
        }
    }

    public function deleteJobType(Request $request)
    {
        if (!$id = $request->id) {
            return $this->responseError(__("nothing_to_delete"));
        }
        if (JobType::where('job_type_id',$id)->delete()) {
            return $this->responseSuccess(__("xin_success_job_type_deleted"));
        }
        return $this->responseError(__("delete_fail"));
    }
    //end job type

    //job category
    public function jobCategory()
    {
        $page_title = __('xin_rec_job_categories');

        return view('admin.constants.job_category', compact('page_title'));
    }

    public function listJobCategory(Request $request) {
        $paginateConfig = PaginateConfig::fromDatatable($request);

        $listJobCategory = $this->getJobCategory($paginateConfig);

        return $this->makeDatatableResponse($listJobCategory, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    public function getJobCategory($paginateConfig)
    {
        $getJobCategory = JobCategory::orderBy('category_id', 'desc');

        $query = $getJobCategory->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());

        return $query;
    }

    public function addJobCategory(Request $request)
    {
        $category_name = $request->job_category;
        $curl = $this->random_string(40);

        $query = JobCategory::create(array('category_name' => $category_name, 'category_url' => $curl));

        if ($query) {
            return $this->responseSuccess(__('xin_success_job_category_added'));
        } else {
            return $this->responseError(__('update_error'));
        }
    }

    public function showJobCategory(Request $request) {
        $id = $request->id;
        $job_category = null;
        if ($id) {
            $job_category = JobCategory::find($id);
        }
        return view('admin.constants.modal.modal_edit_job_category', compact('job_category'));
    }

    public function updateJobCategory(Request $request) {
        $id = $request->id;
        $query = JobCategory::find($id)->update(['category_name' => $request->job_category]);
       
        if ($query) {
            return $this->responseSuccess(__("xin_success_job_category_updated"));
        } else {
            return $this->responseError(__('update_error'));
        }
    }

    public function deleteJobCategory(Request $request)
    {
        if (!$id = $request->id) {
            return $this->responseError(__("nothing_to_delete"));
        }
        if (JobCategory::where('category_id',$id)->delete()) {
            return $this->responseSuccess(__("xin_success_job_category_deleted"));
        }
        return $this->responseError(__("delete_fail"));
    }
    //end job category

    function random_string($len)
	{
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($pool, ceil($len / strlen($pool)))), 0, $len);
	}
}
