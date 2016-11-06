/*jslint browser: true*/
/*global $, jQuery, alert*/
var bcc_base = (function () {
  "use strict";
  function onClick_error(event) {
    var $target = $("#" + $(event.target).attr("for"));
    if ($target.length === 0) {
      $target = $(":input[name=" + $(event.target).attr("for") + "]");
    }
    if (!$target.is(":visible")) {
      $target.closest("div.collapse").expand();
    }
    if (typeof $target[0].select !== "undefined") {
      $target[0].select();
    }
    $target[0].focus();
  }
  function onClick_navLink(event) {
    $(".navbar-collapse").collapse("hide");
  }
  function onReady(event) {
    $(".alert-danger > ul > li").on("click", onClick_error);
    $(".nav > li > a").on("click", onClick_navLink);
  }
  $(document).ready(onReady);
  return {};
}());