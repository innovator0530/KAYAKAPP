<script>
import router from "@/router";

import Topbar from "./topbar";
import Footer from "@/components/footer";

export default {
  components: {
    Topbar,
    Footer
  },
  created() {
    document.body.setAttribute("data-sidebar", "dark");
    document.body.removeAttribute("data-topbar", "light");
    document.body.removeAttribute("data-layout-size", "boxed");
    document.body.removeAttribute("data-layout", "horizontal");
  },
  methods: {
    
  }
};
</script>

<template>
  <div id="layout-wrapper">
    <Topbar />
    <!-- <div class="main-content"> -->
      <div class="page-content">
        <slot />
      </div>
    <!-- </div> -->
    <Footer />
  </div>
</template>

<style scoped>
  .main-content {
    margin-right: 70px;
  }
  .page-content {
    padding: 0 24px 60px 24px !important;
  }
  .footer {
    left: 0;
  }
</style>