/*jslint browser: true*/
/*global $, ga, jQuery, alert*/
var bcc_base = (function () {
  "use strict";
  function createGoogleAnalyticsObject(i, s, o, g, r, a, m) {
    i.GoogleAnalyticsObject = r;
    i[r] = i[r] || function () { (i[r].q = i[r].q || []).push(arguments); };
    i[r].l = +(new Date());
    a = s.createElement(o);
    m = s.getElementsByTagName(o)[0];
    a.async = 1;
    a.src = g;
    m.parentNode.insertBefore(a, m);
  }
  function initDatePickers() {
    $(".date-control").datepicker({ "minDate" : 1 });
  }
  function initTimePickers() {
    $(".time-control").timeEntry({ "ampmPrefix" : " ", "spinnerImage" : "", "timeSteps" : [1, 15, 0] });
  }
  function loadFonts() {
    var urlFonts = "https://fonts.googleapis.com/css?family=Chango|Roboto";
    $(document.createElement("link"))
      .attr("rel", "stylesheet")
      .attr("href", urlFonts)
      .appendTo(document.head);
  }
  function submitGoogleAnalyticsData() {
    ga("create", "UA-87073608-1", "auto");
    ga("send", "pageview");
  }
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
    createGoogleAnalyticsObject(window, document, "script", "https://www.google-analytics.com/analytics.js", "ga");
    submitGoogleAnalyticsData();
    initDatePickers();
    initTimePickers();
    loadFonts();
  }
  $(document).ready(onReady);
  return {};
}());
