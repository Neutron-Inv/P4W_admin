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
            :data-url="urlGenerator('/admin/users/verify/mail')"
            method="post"
            @submit.prevent="submit"
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

            <div class="form-row">
              <div class="form-group col-12 text-center">
                <h6 class="mb-0">{{ $t("verify_email") }}</h6>
                <label
                  >{{ $t("enter_verification_code_sent_to") }}
                  {{ email }}</label
                >
              </div>
            </div>

            <div class="form-row justify-content-center mb-3">
              <div
                class="form-group d-flex justify-content-between"
                style="gap: 8px"
              >
                <input
                  v-for="(digit, index) in 6"
                  :key="index"
                  class="form-control text-center"
                  maxlength="1"
                  type="text"
                  v-model="verificationCode[index]"
                  @input="focusNext(index, $event)"
                  style="width: 45px"
                />
              </div>
            </div>

            <input type="hidden" :value="email" name="email" />

            <div class="form-row">
              <div class="form-group col-12">
                <app-load-more
                  :preloader="preloader"
                  :label="$t('verify')"
                  type="submit"
                  class-name="btn btn-primary btn-block text-center"
                  @submit="submit"
                />
              </div>
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
import { urlGenerator } from "../../../Helpers/AxiosHelper";

export default {
  name: "VerifyEmail",
  props: {
    email: {
      type: String,
      required: true,
    },
  },
  mixins: [ThemeMixin],
  data() {
    return {
      urlGenerator,
      year: moment(moment.now()).format("YYYY"),
      verificationCode: Array(6).fill(""),
      preloader: false,
    };
  },
  methods: {
    submit() {
      const code = this.verificationCode.join("");
      if (code.length < 6) {
        return this.$toastr.e(this.$t("please_enter_all_digits"));
      }

      this.preloader = true;

      this.$axios
        .post(urlGenerator("/admin/users/verify/mail"), {
          email: this.email,
          verification_code: code,
        })
        .then((res) => {
          this.$toastr.s(res.data.message);
          this.$router.push({ name: "users.login.index" });
        })
        .catch((error) => {
          this.$toastr.e(
            error.response?.data?.message || "Verification failed"
          );
        })
        .finally(() => {
          this.preloader = false;
        });
    },
    focusNext(index, event) {
      if (event.target.value.length === 1 && index < 5) {
        this.$el.querySelectorAll('input[type="text"]')[index + 1].focus();
      }
    },
  },
};
</script>
