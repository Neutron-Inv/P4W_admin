<template>
    <div>
        <div class="candidate-application-form">
            <div class="candidate-step-menu custom-scrollbar">
                <div class="toggle-sidebar" @click="toggleSidebar">
                    <div class="bar1" />
                    <div class="bar2" />
                    <div class="bar3" />
                </div>
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <a class="nav-link"
                        :class="[{ 'active': checkActiveTab(basicInformation.key) }, { 'complete': checkCompleteTab(basicInformation.key) }]"
                        :id="`v-pills-${basicInformation.key}-tab`">
                        <span class="step-number">
                            01
                            <span v-if="checkCompleteTab(basicInformation.key)" class="complete-icon">
                                <app-icon name="check" />
                            </span>
                            <span class="step-divider"><span class="divider" /></span>
                        </span>
                        <span class="step-name">{{ basicInformation.title }}</span>
                    </a>
                    <a v-for="(item, index) in applyForm" class="nav-link"
                        :class="[{ 'active': checkActiveTab(item.key) }, { 'complete': checkCompleteTab(item.key) }]"
                        :id="`v-pills-${item.key}-tab`">
                        <span class="step-number">
                            {{ `${(index + 2) > 10 ? (index + 2) : `0${index + 2}`}` }}
                            <span v-if="checkCompleteTab(item.key)" class="complete-icon">
                                <app-icon name="check" />
                            </span>
                            <span class="step-divider"><span class="divider" /></span>
                        </span>
                        <span class="step-name">{{
                            translateLang(item.title)
                        }}</span>
                    </a>
                    <a class="nav-link"
                        :class="[{ 'active': checkActiveTab(finalStep.key) }, { 'complete': checkCompleteTab(finalStep.key) }]"
                        :id="`v-pills-${finalStep.key}-tab`">
                        <span class="step-number">
                            {{ `${(applyForm.length + 2) > 10 ? (applyForm.length + 2) : `0${applyForm.length + 2}`}` }}
                            <span v-if="checkCompleteTab(finalStep.key)" class="complete-icon">
                                <app-icon name="check" />
                            </span>
                            <span class="step-divider"><span class="divider" /></span>
                        </span>
                        <span class="step-name">{{ translateLang(finalStep.title) }}</span>
                    </a>
                </div>
            </div>
            <div class="candidate-step-content">
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade" :class="{ 'show active': checkActiveTab(basicInformation.key) }"
                        :id="`v-pills-${basicInformation.key}`">
                        <h4 class="mb-5">
                            {{ basicInformation.title }}
                        </h4>
                        <div class="row">
                            <div v-for="(field, basicIndex) in basicInformation.fields" class="col-lg-9 col-xl-7"
                                :key="`basic-info-item-${basicIndex}`">
                                <div class="mb-4" v-if="field.show">
                                    <label :for="field.name">{{ $t(field.name) }}<sup v-if="field.require">*</sup></label>
                                    <app-input :type="field.type" :id="field.name"
                                        :list="listGen(field.options, field.type, field)" :disabled="previousCandidate"
                                        :error-message="$errorMessage(errorObj, field.name, false)"
                                        :required="field.require" v-model="submitData.basic_information[field.name]" />
                                </div>
                            </div>
                            <div class="tab-pane-action d-flex justify-content-end col-lg-9 col-xl-7">
                                <button type="button" @click.prevent="goToQuestionStep">
                                    {{ $t('next') }}
                                    <app-icon name="chevron-right" class="ml-2" />
                                </button>
                            </div>
                        </div>
                    </div>
                    <div v-for="(item, parentIndex) in applyForm" class="tab-pane fade"
                        :class="{ 'show active': checkActiveTab(item.key) }">
                        <div class="row">
                            <div class="col-lg-9 col-xl-7">
                                <div>
                                    <h4 class="mb-4">
                                        {{ translateLang(item.title) }}
                                    </h4>
                                    <div v-for="(field, childIndex) in item.fields" class="mb-4">
                                        <div v-if="field.show">
                                            <div v-if="field.type === 'custom-form'" class="d-flex justify-content-between">
                                                <label
                                                    class=" mx-2 mb-3 text-black-50 font-weight-bold text-uppercase font-2xl"
                                                    :for="nameGen(field.name)" style=" letter-spacing: 6px;"> {{
                                                        translateLang(field.name) }}
                                                    <!-- <sup v-if="field.require">*</sup> -->
                                                </label>

                                                <p v-if="errorObj[nameGen(field.name)]"
                                                    class="text-danger validation-error mb-0">{{ translateLang(field.name) +
                                                        ' ' + $t('is_required') }}</p>

                                            </div>
                                            <label v-else :for="nameGen(field.name)">
                                                {{ translateLang(field.name) }}
                                                <!-- <sup v-if="field.require">*</sup> -->
                                            </label>
                                            <template v-if="field.type === 'custom-form'">
                                                <div class="px-2" :key="checkLoop">
                                                    <div class="mb-4" v-for="(subField, subIndex) in field.custom_form"
                                                        :key="'subField' + subIndex">
                                                        <label :for="nameGen(subField.name)">{{ translateLang(subField.name)
                                                        }}
                                                            <!-- <sup v-if="field.require">*</sup> -->
                                                        </label>
                                                        <app-input class="mb-1" :type="subField.type"
                                                            :id="field.name + subField.name + subIndex"
                                                            :list="listGen(subField.options, subField.type, subField)"
                                                            :radio-checkbox-name="(subField.type === 'radio' || subField.type === 'checkbox') ? nameGen(subField.name) : ''"
                                                            :error-message="$errorMessage(errorObj, nameGen(subField.name) + [subIndex], false)"
                                                            :name="field.name + subField.name + subIndex"
                                                            :required="field.require"
                                                            @input="getNestedInput(item.key, nameGen(field.name), field.custom_form, nameGen(subField.name), subField.group, $event)"
                                                            v-model="test[`${nameGen(field.name)}_${subIndex}`]"
                                                            :listItemModelValue="['radio', 'checkbox', 'select'].includes(subField.type) ? 'value' : 'id'" />
                                                        <div class="d-flex justify-content-end">
                                                            <button
                                                                v-if="subField.group > 0 && (subField.group < field?.custom_form[subIndex + 1]?.group || (field.custom_form.length - 1) == subIndex)"
                                                                type="button"
                                                                class="btn text-danger d-inline-flex align-items-center mt-3 p-0"
                                                                @click="deleteSubFields(parentIndex, childIndex, subField.group, item.key, nameGen(field.name))">
                                                                {{ $t('remove') }}
                                                            </button>
                                                        </div>
                                                        <div v-if="subField.group < field?.custom_form[subIndex + 1]?.group"
                                                            class="mb-2 mt-4 border-bottom">
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane-action mb-5">
                                                        <button type="button" class="btn btn-block justify-content-center"
                                                            @click="addSubFields(parentIndex, childIndex)">
                                                            <app-icon name="plus" class="size-14 mr-2" />
                                                            {{ $t('add_new') }}
                                                        </button>
                                                    </div>
                                                </div>

                                            </template>


                                            <app-input v-else :type="field.type" :id="nameGen(field.name)"
                                                :list="listGen(field.options, field.type, field)"
                                                :radio-checkbox-name="(field.type === 'radio' || field.type === 'checkbox') ? nameGen(field.name) : ''"
                                                :error-message="$errorMessage(errorObj, nameGen(field.name), false)"
                                                :required="field.require"
                                                v-model="submitData[item.key][nameGen(field.name)]"
                                                :listItemModelValue="['radio', 'checkbox', 'select'].includes(field.type) ? 'value' : 'id'" />
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane-action">
                                    <button type="button"
                                        @click.prevent="goPrevQuesStep(parentIndex, item.key, parentIndex > 0 ? applyForm[parentIndex - 1].key : '')">
                                        <app-icon name="chevron-left" class="mr-2" />
                                        {{ $t('previous') }}
                                    </button>
                                    <button type="button"
                                        @click.prevent="goNextQuesStep(parentIndex, item.key, parentIndex < (applyForm.length - 1) ? applyForm[parentIndex + 1].key : '')">
                                        {{ $t('next') }}
                                        <app-icon name="chevron-right" class="ml-2" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" :class="{ 'show active': checkActiveTab(finalStep.key) }"
                        :id="`v-pills-${finalStep.key}`">
                        <div>
                            <h4 class="mb-2">
                                {{ finalStep.title }} - {{ appliedDone ? $t('submitted') : $t('in_progress') }}
                            </h4>
                            <div v-if="activeTab === 'submit'" class="row mb-4">
                                <div class="col-12 min-height-300 py-primary">
                                    <h5 class="mb-3">{{ basicInformation.title }}</h5>
                                    <hr />
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <td class="w-50">
                                                    <span class="text-muted">{{ $t('first_name') }}</span>
                                                    <p class=" fs-6">{{ submitApplicant.first_name }}</p>
                                                </td>
                                                <td class="w-50">
                                                    <span class="text-muted">{{ $t('last_name') }}</span>
                                                    <p class=" fs-6">{{ submitApplicant.last_name }}</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="w-50">
                                                    <span class="text-muted">{{ $t('email') }}</span>
                                                    <p class=" fs-6">{{ submitApplicant.email }}</p>
                                                </td>
                                                <td class="w-50">
                                                    <span class="text-muted">{{ $t('phone') }}</span>
                                                    <p class=" fs-6">{{ submitApplicant.phone }}</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="w-50" v-if="submitApplicant.date_of_birth">
                                                    <span class="text-muted">{{ $t('date_of_birth') }}</span>
                                                    <p class=" fs-6">{{ submitApplicant.date_of_birth }}</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <h5 class="mt-3">{{ $t('others_information') }}</h5>
                                    <hr />
                                    <template v-if="tempFormData.question_answer.length">
                                        <div v-for="(ans, ansIndex) in tempFormData.question_answer" v-if="ans.answer"
                                            class="mt-1 p-2">
                                            <div class="row" v-if="typeof ans.answer === 'string'">
                                                <span class="text-muted col-3 col-xl-2">{{ questionTitle(ans.question)
                                                }}</span>
                                                <p class="mb-0 col-9 col-xl-10">{{ ans.answer }}</p>
                                            </div>
                                            <div v-else>
                                                <h6 class="mb-3 text-black-50 text-uppercase mt-4"
                                                    style=" letter-spacing: 6px;">
                                                    {{
                                                        questionTitle(ans.question) }}</h6>
                                                <div class="" v-for="(value, tempIndex) in ans.answer"
                                                    :key="`item${tempIndex}`">
                                                    <div v-for="(labelValue, labelName, subIndex) in value"
                                                        :key="labelName + subIndex" class="row">
                                                        <div class="col-3 col-xl-2">
                                                            <p class="mb-1 text-muted">{{ $t(labelName) }}</p>
                                                        </div>
                                                        <div class="col-9 col-xl-10">
                                                            <p>{{ labelValue }}</p>
                                                        </div>
                                                    </div>
                                                    <hr v-if="tempIndex < Object.keys(value).length - 1" />
                                                    <div v-else class="pb-4"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane-action" :class="{ 'justify-content-end': appliedDone }">
                            <button v-if="!appliedDone" type="button" @click.prevent="goToQuestionLastStep">
                                <app-icon name="chevron-left" class="mr-2" />
                                {{ $t('previous') }}
                            </button>
                            <app-load-more :preloader="preloader" :label="$t('submit')"
                                :class-name="`shadow-none ${appliedDone ? 'disabled' : ''}`" @submit="submitApplication" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Email Verification Modal -->
        <candidate-email-verification-modal v-if="emailVerificationModal" :job-post-id="applyFormData.id"
            @dontVerified="goToJobPostView" @verifiedData="afterVerifiedEmail" />

        <app-confirmation-modal v-if="showConfirmationModal" :icon="confirmationModalIcon" :hide-second-button="true"
            :title="confirmationModalTitle" :message="confirmationModalMessage" :modal-class="confirmationModalClass"
            modal-id="confirmation-modal" :first-button-name="$t('ok')" :second-button-name="''" />
    </div>
</template>

<script>
import { FormMixin } from "../../../../core/mixins/form/FormMixin";
import DateFunction from "../../../../core/helpers/date/DateFunction";
import { formatDateToLocal } from "../../../Helpers/DateTimeHelper";
import { PUBLIC_JOB_POST } from "../../../Config/ApiUrl";
import { axiosPost } from "../../../Helpers/AxiosHelper";
import { snakeCase, ucFirst } from "../../../Helpers/TextHelper";
import { getApplyFormBasicInformation } from "../../../Mixins/app/ApplyFormBasicInformation";
import { TENANT_BASE_URL } from "../../../Config/UrlHelper";

export default {
    name: 'CandidateApplicationForm',
    mixins: [FormMixin],
    props: {
        applyFormData: {}
    },
    data() {
        return {
            test: {},
            checkLoop: 1,
            formatDateToLocal,
            showConfirmationModal: false,
            confirmationModalMessage: '',
            confirmationModalTitle: '',
            emailVerificationModal: false,
            previousCandidate: false,
            confirmationModalClass: '',
            confirmationModalIcon: '',
            // Error Handler
            errorObj: {},

            // Other Data
            finalStep: {
                key: 'submit',
                title: this.$t('submit_application')
            },
            basicInformation: {
                title: this.$t('basic_information'),
                key: 'basic_info',
                type: 'custom_field',
                isVisible: true,
                icon: 'user',
                fields: getApplyFormBasicInformation(this.$t)
            },
            submitData: {
                basic_information: {}
            },
            tempFormData: {
                question_answer: []
            },

            // Styles
            appliedDone: false,
            preloader: false,
            activeTab: 'basic_info',
            completeTabs: [],
            defaultApplicationSectionTitles: ['contact_details', 'education_experience', 'questions', 'assignment', 'resume']
        }
    },
    computed: {
        applyForm() {
            let data = typeof this.applyFormData['apply_form_settings'] === 'string' ?
                JSON.parse(this.applyFormData['apply_form_settings']) : this.applyFormData['apply_form_settings'];
            return data.filter((item) => {
                item.fields.forEach((ele, index) => {
                    if (ele.type === "custom-form") {
                        ele.custom_form.forEach((subItem, subIndex) => {
                            subItem.group = 0
                        })
                    }
                })
                return item.isVisible
            });
        },
        questions() {
            let question = []
            this.applyForm.forEach(item => {
                question = [...question, ...item.fields]
            })
            return question.map(el => el.name);
        },
        submitApplicant() {
            return this.submitData.basic_information
        }
    },
    beforeMount() {
        this.applyForm.forEach(item => {
            this.submitData[item.key] = {};
        })
    },
    mounted() {
        this.emailVerificationModal = true;
    },
    methods: {
        getNestedInput(keyName, fileName, customList, fileSubName, index, $event) {
            if (!this.submitData[keyName]) {
                Vue.set(this.submitData, keyName, {});
            }
            if (!this.submitData[keyName][fileName]) {
                Vue.set(this.submitData[keyName], fileName, []);
            }
            if (this.submitData[keyName][fileName].length === index) {
                let obj = {};
                customList.forEach(item => {
                    obj[this.nameGen(item.name)] = '';
                })
                obj[fileSubName] = $event;
                this.submitData[keyName][fileName].push(obj);
            } else {
                this.submitData[keyName][fileName][index][fileSubName] = $event;
            }
        },

        addSubFields(parentIndex, childIndex) {
            const tempArray = this.applyForm[parentIndex].fields[childIndex].custom_form
            let maxGroup = Math.max(...tempArray.map(el => el.group));
            let newElements = tempArray.filter(el => el.group === 0).map(el => ({ ...el, group: maxGroup + 1 }));
            tempArray.push(...newElements);
            this.$set(this.applyForm[parentIndex].fields[childIndex], 'custom_form', tempArray);
            this.checkLoop++;
        },
        deleteSubFields(parentIndex, childIndex, eleGroupId, keyName, fieldName) {
            this.applyForm[parentIndex].fields[childIndex].custom_form = this.applyForm[parentIndex].fields[childIndex].custom_form.filter(item => item.group !== eleGroupId)
            // if(this.submitData[keyName][fieldName][eleGroupId])
            // this.submitData[keyName][fieldName].splice(eleGroupId, 1)

            this.checkLoop--;
        },
        confirmed() { },
        cancelled() { },
        afterVerifiedEmail(email, data) {
            if (data) {
                this.submitData.applicant_id = data.id;
                this.submitData.basic_information = data;
                this.previousCandidate = true;
            } else this.submitData.basic_information.email = email
            this.emailVerificationModal = false;
        },
        toggleSidebar() {
            $('.toggle-sidebar').toggleClass('change');
            $('.candidate-step-menu').toggleClass('active');
        },
        checkActiveTab(tab) {
            return this.activeTab === tab
        },
        checkCompleteTab(tab) {
            return this.completeTabs.includes(tab)
        },
        questionTitle(ques) {
            let question = this.questions.find(item => this.nameGen(item) === ques)
            return question ? question : '';
        },

        /***
         * Methods for generating Form
         ***/

        nameGen(name) {
            return _.snakeCase(_.lowerCase(name));
        },
        listGen(options, type, field) {

            let useLang = ['gender', 'email'];

            if (!(type === 'radio' || type === 'checkbox' || type === 'select')) return [];
            if (this.isUndefined(options) || options.length < 1) return [];
            return options.map(item => {
                return {
                    id: this.nameGen(item),
                    value: useLang.includes(field.name) ? this.$t(item) : item
                }
            })
        },
        goToQuestionStep() {
            if (this.submitData.applicant_id) {
                this.tempFormData.applicant_id = this.submitData.applicant_id;
                this.readyToGoQuestionStep();
                return;
            }
            this.checkBasicInfoValidation();
        },
        checkBasicInfoValidation() {
            this.errorObj = {};
            this.fieldStatus = {
                isSubmit: true
            };
            this.basicInformation.fields.forEach(item => {
                //let name = this.nameGen(item.name);
                let name = item.name;
                if (item.require && !this.submitData.basic_information[name]) {
                    this.errorObj[name] = this.$t('this_field_is_required')
                }
                if (item.type === 'email' && !this.isValidEmail(this.submitData.basic_information[name])) {
                    this.errorObj[name] = this.$t('this_field_is_invalid')
                }
                if (item.type === 'date' && this.submitData.basic_information[name]) {
                    this.submitData.basic_information[name] = DateFunction.getDateFormatForBackend(this.submitData.basic_information[name]);
                }
            })
            if (Object.keys(this.errorObj).length < 1) {
                this.tempFormData.basic_information = this.submitData.basic_information;
                this.readyToGoQuestionStep();
            }
        },
        readyToGoQuestionStep() {
            this.completeTabs.push(this.basicInformation.key);
            if (this.applyForm.length > 0)
                this.activeTab = this.applyForm[0].key;
            else {
                this.prepareFormData();
                this.activeTab = this.finalStep.key;
            }
        },

        /***
         * Questions Steps
         ***/
        goNextQuesStep(index, currentKey, nextKey) {
            this.checkQuestionsValidation(index, currentKey, nextKey)
        },
        goPrevQuesStep(index, currentKey, prevKey) {
            if (prevKey) this.activeTab = prevKey;
            else this.activeTab = 'basic_info';
        },

        checkQuestionsValidation(index, key, nextKey) {
            this.errorObj = {};
            this.fieldStatus = {
                isSubmit: true
            };
            this.applyForm[index].fields.forEach((item, indexApply) => {
                if (!item.show) return;
                let name = this.nameGen(item.name);
                if (item.require && !this.submitData[key][name]) {
                    this.errorObj[name] = this.$t('this_field_is_required')
                }
                if (item.type === 'email' && !this.isValidEmail(this.submitData[key][name])) {
                    this.errorObj[name] = this.$t('this_field_is_invalid')
                }
                if (item.type === 'dropzone' && item.require && !this.submitData[key][name]?.length) {
                    this.errorObj[name] = this.$t('this_field_is_required');
                }
                if (item.type === 'dropzone' && this.submitData[key][name]?.length > 1) {
                    this.errorObj[name] = this.$t('this_field_require_only_one_attachment');
                }
                if (item.type === 'dropzone' && this.submitData[key][name]?.length === 1) {
                    let fileName = this.submitData[key][name][0].name.toLowerCase().split('.'),
                        extension = fileName[fileName.length - 1],
                        acceptableTypes = ['pdf', 'docx', 'doc', 'png', 'jpeg', 'jpg', 'xlsx', 'xls', 'csv']
                    if (!acceptableTypes.includes(extension)) {
                        this.errorObj[name] = this.$t('this_field_format_should_be_pdf_doc_docx');
                    }
                }
                if (item.type === 'date' && this.submitData[key][name]) {
                    this.submitData[key][name] = DateFunction.getDateFormatForBackend(this.submitData[key][name]);
                }
                if (item.type === 'custom-form') {
                    item.custom_form.forEach((el, idx) => {
                        let subName = this.nameGen(el.name);

                        if (item.require && this.submitData[key] && this.submitData[key][name] && this.submitData[key][name][el.group] && (this.submitData[key][name][el.group] == undefined || !this.submitData[key][name][el.group][subName])) {
                            this.errorObj[subName + idx] = this.$t('this_field_is_required')
                        }
                    })

                }
            })
            if (Object.keys(this.errorObj).length < 1) {
                this.completeTabs.push(key);
                if (nextKey) this.activeTab = nextKey;
                else {
                    this.prepareFormData();
                    this.activeTab = this.finalStep.key;
                }
            }
        },
        goToQuestionLastStep() {
            if (this.applyForm.length > 0)
                this.activeTab = this.applyForm[this.applyForm.length - 1].key;
            else this.activeTab = 'basic_info';
        },
        submitApplication() {
            this.completeTabs.push(this.finalStep.key);
            this.preloader = true;
            this.finalSubmit();
        },
        prepareFormData() {
            const tempArray = []
            this.tempFormData.question_answer = []
            this.applyForm.forEach(item => {
                item.fields.forEach((el) => {
                    let ans = this.submitData[item.key][this.nameGen(el.name)];

                    const formateAns = (ansTemp) => {
                        if (typeof ansTemp === 'string') {
                            return ansTemp;
                        }
                        else if (Array.isArray(ansTemp) && !(ansTemp[0] instanceof File)) {
                            return ansTemp;
                        } else {
                            return ''
                        }
                    }
                    if (Array.isArray(ans) && !(ans[0] instanceof File)) {
                        let newArray = ans.map(obj => {
                            let newObj = {};
                            for (let key in obj) {
                                if (obj[key] !== "") {
                                    newObj[key] = obj[key];
                                }
                            }
                            return newObj;
                        });
                        ans = newArray
                    }
                    if (ans && !tempArray.includes(el.name)) {
                            this.tempFormData.question_answer.push({
                                question: this.nameGen(el.name),
                                answer: formateAns(ans),
                                attachment: (Array.isArray(ans) && ans[0] instanceof File) ? ans[0] : ''
                            })

                    }
                    tempArray.push(el.name)
                })
            })

        },
        finalSubmit() {
            this.tempFormData.apply_form_setting = typeof this.applyFormData['apply_form_settings'] === 'string' ?
                JSON.parse(this.applyFormData['apply_form_settings']) : this.applyFormData['apply_form_settings'];
            let url = `${TENANT_BASE_URL}/${this.applyFormData.slug}/apply`,
                formData = new FormData;
                

            for (const [key, value] of Object.entries(this.tempFormData)) {
                if (key === 'question_answer') {
                    let index = 0;
                    value.forEach(item => {
                        formData.append(`question_answer[${index}][question]`, item.question);
                        formData.append(`question_answer[${index}][attachment]`, item.attachment);
                        if (typeof item.answer !== 'string') {
                            formData.append(`question_answer[${index}][answer]`, JSON.stringify(item.answer));
                        } else {
                            formData.append(`question_answer[${index}][answer]`, item.answer);
                        }
                        index++;
                    })
                } else if (key === 'basic_information') {
                    for (const [field, data] of Object.entries(value)) {
                        formData.append(`basic_information[${field}]`, data)
                    }
                } else if (key === 'applicant_id') {
                    formData.append('applicant_id', value);
                } else if (key === 'apply_form_setting') {
                    formData.append('apply_form_setting', JSON.stringify(value));
                }
            }

            // formData = formDataAssigner(new FormData(), formData);

            axiosPost(url, formData).then((res) => {
                this.confirmationModalMessage = res.data.message;
                this.confirmationModalClass = 'success';
                this.confirmationModalTitle = this.$t('thank_you');
                this.confirmationModalIcon = 'check-circle';
                this.preloader = false;
                // this.$toastr.s(res.data.message);
                this.appliedDone = true;
            }).catch(({ response }) => {
                this.confirmationModalMessage = response.data.message;
                this.confirmationModalTitle = this.$t('oops');
                this.confirmationModalClass = 'danger';
                this.confirmationModalIcon = 'x-circle';
                // this.$toastr.e(response.data.message);
            }).finally(() => {
                this.showConfirmationModal = true;
                this.preloader = false;
            })
        },
        goToJobPostView() {
            window.location = this.getAppUrl(`${TENANT_BASE_URL}/${this.applyFormData.slug}`);
        },
        translateLang(item) {
            return this.$t(snakeCase(item.toLowerCase())).includes("_") ? ucFirst(this.$t(item)) : ucFirst(this.$t(snakeCase(item.toLowerCase())));
        }
    }
}
</script>