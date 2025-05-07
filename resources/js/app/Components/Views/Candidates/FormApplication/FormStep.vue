<template>
    <form ref="form" data-url="">
        <div v-for="(item, index) in formData.items" :key="`item-${item.id}-${index}`">
            <div v-for="(field, fieldIndex) in item.fields" :key="`i-${index}-field-${field.id}-${fieldIndex}`">
                <div class="mb-4" v-if="field.is_visible">
                    <!-- custom form -->
                    <template v-if="field.type === 'custom-form'">
                        <div class="d-flex justify-content-between align-items-center">
                            <label class="mb-3 text-black-50 font-weight-bold text-uppercase font-2xl"
                                style=" letter-spacing: 6px;">
                                {{ field.title }}<sup v-if="field.required">*</sup>
                            </label>
                            <button type="button"
                                class="border-0 text-danger default-base-color width-30 height-30 rounded-pill d-inline-flex align-items-center justify-content-center"
                                v-if="field.removeable" @click="item.fields.splice(fieldIndex, 1)">
                                <app-icon name="x" />
                            </button>
                        </div>
                        <div class="border-bottom">
                            <div class="mb-4" v-for="(f, i) in field.fields" :key="`i-${index}-f-${fieldIndex}-custom-fields-${f.id}-${i}`">
                                <label class="fahim">{{ f.title }} <sup v-if="f.required">*</sup></label>
                                <template v-if="!('dateOptions' in f)">
                                    <app-input
                                        :name="nameGen(`${field.title}-${index}-${fieldIndex}-${f.title}-${i}`)"
                                        :id="`i-${index}-f-${fieldIndex}-item-${item.id}-${i}`"
                                        :key="`i-${index}-f-${fieldIndex}-item-${item.id}-${i}`"
                                        :radioCheckboxName="`i-${index}-f-${fieldIndex}-item-${item.id}-${i}`"
                                        v-model="f.value"
                                        :placeholder="'Enter ' + f.title"
                                        :list="(f.options || []).map(i => ({ id: i, value: i }))"
                                        :type="f.type"
                                        :required="f.required"
                                        :disabled="f.disabled"
                                    />
                                </template>
                                <template v-else>
                                    <app-input
                                        :name="nameGen(`${field.title}-${index}-${fieldIndex}-${f.title}-${i}`)"
                                        :id="`i-${index}-f-${fieldIndex}-item-${item.id}-${i}`"
                                        :key="`i-${index}-f-${fieldIndex}-item-${item.id}-${i}`"
                                        :radioCheckboxName="`i-${index}-f-${fieldIndex}-item-${item.id}-${i}`"
                                        v-model="f.value"
                                        :placeholder="'Enter ' + f.title"
                                        :list="(f.options || []).map(i => ({ id: i, value: i }))"
                                        :type="f.type"
                                        :required="f.required"
                                        :disabled="f.disabled"
                                        v-bind="f.dateOptions"
                                    />
                                </template>
                                <small class="text-danger" v-if="true">{{
                                    errors[nameGen(`${field.title}-${index}-${fieldIndex}-${f.title}-${i}`)] }}</small>
                            </div>
                        </div>
                    </template>
                    <template v-else>
                        <div class="d-flex justify-content-between align-items-center">
                            <label>{{ field.title }}<sup v-if="field.required">*</sup></label>
                            <button type="button"
                                class="border-0 text-danger default-base-color width-30 height-30 rounded-pill d-inline-flex align-items-center justify-content-center"
                                v-if="field.removeable" @click="item.fields.splice(fieldIndex, 1)">
                                <app-icon name="x" />
                            </button>
                        </div>
                        <template v-if="!('dateOptions' in field)">
                            <app-input
                                :type="field.type"
                                :placeholder="'Enter ' + field.title"
                                :id="`i-${index}-item-${fieldIndex.toString()}`"
                                :key="`i-${index}-item-${item.id}-${fieldIndex}`"
                                :name="nameGen(`${field.title}-${index}-${fieldIndex}`)" 
                                :field-name="nameGen(`i-${index}-${field.title}-${index}-${fieldIndex}`)"
                                :radioCheckboxName="nameGen(`i-${index}-${field.title}-${index}-${fieldIndex}`)"
                                :list="(field.options || []).map(i => ({ id: i, value: i }))"
                                :required="field.required"
                                v-model="field.value"
                                v-bind="field.type === 'tel-input' ? {validCharactersOnly: true} : {}"
                                :disabled="field.disabled"
                            />
                        </template>
                        <template v-else>
                            <app-input
                                :type="field.type"
                                :placeholder="'Enter ' + field.title"
                                :id="`i-${index}-item-${fieldIndex.toString()}`"
                                :key="`i-${index}-item-${item.id}-${fieldIndex}`"
                                :name="nameGen(`${field.title}-${index}-${fieldIndex}`)" 
                                :field-name="nameGen(`i-${index}-${field.title}-${index}-${fieldIndex}`)"
                                :radioCheckboxName="nameGen(`i-${index}-${field.title}-${index}-${fieldIndex}`)"
                                :list="(field.options || []).map(i => ({ id: i, value: i }))"
                                :required="field.required"
                                v-model="field.value"
                                v-bind="field.dateOptions"
                            />
                        </template>

                        <small class="text-danger" v-if="true">{{ errors[nameGen(`${field.title}-${index}-${fieldIndex}`)]
                        }}</small>

                    </template>
                    <div>
                        <button class="btn primary-text-color d-inline-flex align-items-center px-0" v-if="field.duplicate"
                            @click="add_more(item, field, fieldIndex)">{{ $t('add_more') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane-action">
            <button v-if="!(index < 1)" type="button" @click.prevent="goPrevQuesStep">
                <app-icon name="chevron-left" class="mr-2" />
                {{ $t('previous') }}
            </button>
            <div v-else></div>
            <button v-if="steps > (index + 1)" type="button" @click.prevent="goNextQuesStep()">
                {{ $t('next') }}
                <app-icon name="chevron-right" class="ml-2" />
            </button>
            <button v-else type="button" @click.prevent="goNextQuesStep(true)">
                {{ $t('finish') }}
            </button>
        </div>
    </form>
</template>

<script>
import { FormMixin } from "../../../../../core/mixins/form/FormMixin";
import _ from 'lodash'
export default {
    mixins: [FormMixin],
    props: ['formBody', 'steps', 'index', 'selectedForm'],
    data() {
        return {
            formData: _.cloneDeep(this.selectedForm || this.formBody),
            errors: {}
        }
    },
    methods: {
        goNextQuesStep(isFinalStep = false) {
            let errorListComponents = this.isValidForm()
            this.errors = {}
            for (let component of errorListComponents) {
                let name = component.$attrs.name;
                this.errors[name] = this.$t("this_field_is_required");
            }
            if (!errorListComponents.length) {
                this.$emit('next', this.formData, isFinalStep)
            }
        },
        goPrevQuesStep() {
            this.$emit('previous')
        },
        add_more(item, field, index) {
            let f = _.cloneDeep(field)
            delete field.duplicate
            field.removeable = true
            if (f.type === 'custom-form') {
                f.fields = f.fields.map(f => ({ ...f, value: '' }))
            } else {
                delete f.value
            }
            let fields = this.formData.items.find(i => i.id === item.id).fields
            fields.splice(index + 1, 0, f);
        },
        nameGen(value) {
            return value.split(' ').map(i => i.toLowerCase()).join('-')
        },
        removeField(item, field, index) {
            let fields = this.formData.items.find(i => i.id === item.id).fields
            fields.splice(index, 0);
        },
        isValidForm() {
            let instance = this,
                childComponents = this.getInputComponents(),
                invalidFields = [],
                message = "";

            childComponents.filter((item) => {
                instance.fields[item.name] = item.value;

                if (item.required && (!item.value || item.value.length === 0)) {

                    message = this.$t("this_field_is_required");
                    invalidFields.push(item);
                    instance.makeFieldStatusObject(item, message, "required");

                } else if (item.type === "password") {
                    if (item.specialValidation && this.isValidPassword(item.value)) {
                        if (!_.isEmpty(item.sameAs)) {

                            message = this.$t("passwords_are_not_matched");
                            if (instance.fields[item.sameAs] !== item.value) {

                                invalidFields.push(item);
                                instance.makeFieldStatusObject(item, message, "sameAs", "sameAs");
                            }

                        }
                    } else if (!item.specialValidation) {
                        if (!_.isEmpty(item.sameAs)) {

                            if (instance.fields[item.sameAs] || item.value) {
                                message = this.$t("passwords_are_not_matched");
                                if (instance.fields[item.sameAs] !== item.value) {
                                    invalidFields.push(item);
                                    instance.makeFieldStatusObject(item, message, "sameAs", "sameAs");
                                }
                            }

                        }
                    } else {
                        message = this.$t("please_enter_a_strong_password");
                        invalidFields.push(item);
                        instance.makeFieldStatusObject(item, message, "password");
                    }

                } else if (item.type === "date" && !_.isEmpty(item.notBefore)) {

                    message = `${item.name} ${this.$t("can_not_before")} ${item.notBefore}`;
                    if (!instance.isSecondDateAfterFirstDate(instance.fields[item.notBefore], item.value)) {

                        invalidFields.push(item);
                        instance.makeFieldStatusObject(item, message, "notBefore", "notBefore");
                    }

                } else if (item.type === "email" && !this.isValidEmail(item.value)) {

                    message = this.$t("this_field_is_invalid");
                    invalidFields.push(item);
                    instance.makeFieldStatusObject(item, message, "email");

                } else if (item.type === "time" && this.isValidTime(item.value)) {

                    message = this.$t("this_field_is_invalid");
                    invalidFields.push(item);
                    instance.makeFieldStatusObject(item, message, "time");

                } else {
                    let itemLength = item.value ? item.value.length : null;

                    if (itemLength && item.minLength && itemLength < item.minLength) {

                        message = `${this.$t("minimum_length_is")} ${item.minLength}.`;
                        invalidFields.push(item);
                        instance.makeFieldStatusObject(item, message, "minLength");

                    } else if (itemLength && item.maxLength && itemLength > item.maxLength) {

                        message = `${this.$t("maximum_length_is")} ${item.maxLength}.`;
                        invalidFields.push(item);
                        instance.makeFieldStatusObject(item, message, "maxLength");

                    } else if (item.minNumber && item.value < item.minNumber) {

                        message = `${this.$t("minimum_number_is")} ${item.minNumber}.`;
                        invalidFields.push(item);
                        instance.makeFieldStatusObject(item, message, "minNumber");

                    } else if (item.maxNumber && item.value > item.maxNumber) {

                        message = `${this.$t("maximum_number_is")} ${item.maxNumber}.`;
                        invalidFields.push(item);
                        instance.makeFieldStatusObject(item, message, "maxNumber");

                    } else if (item.alphanumeric && !instance.isAlphanumeric(item.value)) {

                        message = this.$t("this_field_is_not_alphanumeric");
                        invalidFields.push(item);
                        instance.makeFieldStatusObject(item, message, "alphanumeric");
                    }
                }
            });
            return invalidFields;
        },
    }
}
</script>

<style lang="scss" scoped></style>
