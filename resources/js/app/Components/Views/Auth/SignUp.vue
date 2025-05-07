<template>
  <div class="container-fluid p-0">
    <div class="row">
      <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-8">
        <div
          class="back-image"
          :style="
            'background-image: url(' +
            urlGenerator(configData.company_banner) +
            ')'
          "
        ></div>
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 pl-md-0">
        <div class="login-form d-flex align-items-center">
          <form
            class="sign-in-sign-up-form w-100"
            ref="form"
            data-url="/auth/user/register"
            action="store"
          >
            <div class="text-center mb-4">
              <img
                :src="
                  urlGenerator(configData.company_logo)
                    ? urlGenerator(configData.company_logo)
                    : urlGenerator('/images/core.png')
                "
                alt=""
                class="img-fluid logo"
              />
            </div>
            <div v-if="!showVerification">
              <div class="form-row">
                <div class="form-group col-12">
                  <h6 class="text-center mb-0">{{ $t("sign_up") }}</h6>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-12">
                  <label for="user_first_name">{{ $t("first_name") }}</label>
                  <app-input
                    type="text"
                    v-model="user.first_name"
                    :placeholder="$t('enter_first_name')"
                    :required="true"
                  />
                </div>
              </div>

              <div class="form-row">
                <div class="form-group col-12">
                  <label for="user_last_name">{{ $t("last_name") }}</label>
                  <app-input
                    type="text"
                    v-model="user.last_name"
                    :placeholder="$t('enter_last_name')"
                    :required="true"
                  />
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-12">
                  <label for="user_last_name">{{ $t("email") }}</label>
                  <app-input
                    type="text"
                    v-model="user.email"
                    :placeholder="$t('enter_email')"
                    :required="true"
                  />
                </div>
              </div>

              <div class="form-row">
                <div class="form-group col-12">
                  <label for="user_password">{{ $t("password") }}</label>
                  <app-input
                    type="password"
                    v-model="user.password"
                    :specialValidation="true"
                    :placeholder="$t('enter_your_password')"
                    :required="true"
                    :show-password="true"
                  />
                  <PasswordWarning />
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-12">
                  <label for="user_password_confirmation">{{
                    $t("confirm_password")
                  }}</label>
                  <app-input
                    type="password"
                    same-as="user_password"
                    :show-password="true"
                    v-model="user.password_confirmation"
                    :placeholder="$t('confirm_password')"
                    :required="true"
                  />
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-12">
                  <app-load-more
                    :preloader="preloader"
                    :label="$t('sign_up')"
                    type="submit"
                    class-name="btn btn-primary btn-block text-center"
                    @submit="submit"
                  />
                </div>
              </div>
            </div>
            <div v-else>
              <div class="form-row">
                <div class="form-group col-12">
                  <h6 class="text-center mb-0">{{ $t("verify_email") }}</h6>
                  <p class="text-center text-muted">
                    {{ $t("verification code sent") }}
                  </p>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-12">
                  <label for="verification_code">{{
                    $t("Verification Code")
                  }}</label>
                  <app-input
                    type="text"
                    name="verification_code"
                    v-model="verificationCode"
                    :placeholder="$t('Enter Verification Code')"
                    :required="true"
                    maxlength="6"
                  />
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-12">
                  <app-load-more
                    :preloader="preloader"
                    :label="$t('verify')"
                    type="submit"
                    class-name="btn btn-primary btn-block text-center"
                    @submit="verifyCode"
                  />
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-12 text-center">
                  <a href="#" @click.prevent="resendCode" class="bluish-text">
                    {{ $t("Resend Code") }}
                  </a>
                </div>
              </div>
            </div>
            <div
              class="form-row form-row flex-column flex-md-row justify-content-center justify-content-md-between justify-content-lg-between"
            >
              <a
                :href="urlGenerator('/admin/users/login')"
                class="bluish-text d-flex align-items-center justify-content-center justify-content-lg-end"
              >
                <app-icon name="lock" class="pr-2" />
                {{ $t("login") }}
              </a>
            </div>
            <div class="form-row">
              <div class="col-12">
                <p class="text-center mt-5">
                  {{
                    $t("copyright_text") +
                    " " +
                    year +
                    " " +
                    $t("by") +
                    " " +
                    configData.company_name
                  }}
                </p>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ThemeMixin from "../../../../core/mixins/global/ThemeMixin";
import PasswordWarning from "./PasswordWarning";
import { AuthMixin } from "./Mixins/AuthMixin";
import { urlGenerator } from "../../../Helpers/AxiosHelper";

export default {
  name: "SignUp",
  mixins: [AuthMixin, ThemeMixin],
  components: {
    PasswordWarning,
  },
  props: {
    userInfo: {},
    siteKey: String,
    recaptchaEnable: {},
  },
  data() {
    return {
      urlGenerator,
      user: {},
      userData: {},
      year: moment(moment.now()).format("YYYY"),
      showVerification: false,
      verificationCode: "",
      tempUserData: null,
    };
  },
  created() {
    if (this.userInfo) {
      this.userData = JSON.parse(this.userInfo);
    }
  },
  methods: {
    submit() {
      console.log("Submitting registration form");
      let data = this.user;
      this.tempUserData = { ...data };

      // Show verification step immediately
      this.showVerification = true;
      this.$toastr.s(this.$t("verification code sent"));

      // Then save the data
      this.save(data);
    },
    afterSuccess(res) {
      console.log("Registration response:", res);
      if (this.showVerification && !this.verificationCode) {
        // First registration success
        this.preloader = false;
      } else if (this.showVerification && this.verificationCode) {
        // Verification success
        this.$toastr.s(res.data.message);
        setTimeout(() => {
          location.replace(urlGenerator("/admin/users/login"));
        }, 1000);
      }
    },
    verifyCode() {
      if (!this.verificationCode) {
        this.$toastr.e(this.$t("Please enter verification code"));
        return;
      }

      let data = {
        ...this.tempUserData,
        verification_code: this.verificationCode,
      };

      this.save(data);
    },
    resendCode() {
      let data = this.tempUserData;
      this.save(data);
      this.$toastr.s(this.$t("verification code resent"));
    },
  },
};
</script>
