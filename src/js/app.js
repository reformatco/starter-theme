import 'babel-polyfill';
import $ from 'jquery';
import Flickity from 'flickity';
import 'flickity-fade';
import fitVids from './plugins/jquery.fitvids';
import './components/aria-menu';
import Stickyfill from './components/stickyfill.es6';

const isPhone = () => {
  return (typeof window.orientation !== "undefined") || (navigator.userAgent.indexOf('IEMobile') !== -1);
};

// Check for modern browser

if ('querySelector' in document && 'addEventListener' in window) {
  document.documentElement.classList.remove('no-js');
  document.documentElement.classList.add('js');
}

let state = {
  // navOpen from aria-menu
  navOpen: true,
  searchOpen: false,
  flkty: {},
};

$(() => {

  const pageLoad = () => {

    if ($('.m--video .video').length) {
      $('.m--video .video').fitVids();
    }

    const stickyelem = $('.sticky');
    Stickyfill.add(stickyelem);

    let disabledHandle;
    const navbar = document.querySelector('#modal-nav');

    $('.navbar-burger').on('click', () => {
      if (!state.navOpen) {
        if (state.searchOpen) {
          $('#searchtoggle').attr('aria-expanded','false');
          document.getElementById("site-search").blur();
          state.searchOpen = false;
          $('body').removeClass('search-active');
        }
      }
      state.navOpen = !state.navOpen;
    });

    $('#searchtoggle').on('click', () => {
      $('body').toggleClass('search-active');
      if (!state.searchOpen) {
        $('#searchtoggle').attr('aria-expanded','true');
        document.getElementById("site-search").focus();
      } else {
        $('#searchtoggle').attr('aria-expanded','false');
        document.getElementById("site-search").blur();
      }
      state.searchOpen = !state.searchOpen;
    });

  }



  pageLoad();

});
