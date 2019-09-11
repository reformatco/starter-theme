import 'babel-polyfill';
import $ from 'jquery';
import fitVids from './plugins/jquery.fitvids';

const isPhone = () => {
  return (typeof window.orientation !== "undefined") || (navigator.userAgent.indexOf('IEMobile') !== -1);
};

// Check for modern browser

if ('querySelector' in document && 'addEventListener' in window) {
  document.documentElement.classList.remove('no-js');
  document.documentElement.classList.add('js');
}

let state = {
  navOpen: false,
  searchOpen: false,
  subnavOpen: false,
  flkty: {},
};

$(() => {

  const pageLoad = () => {

    if ($('.m--video .video').length) {
      $('.m--video .video').fitVids();
    }

    let disabledHandle;
    const navbar = document.querySelector('#modal-nav');

    $('#burger').on('click', () => {
      $('body').toggleClass('nav-active');
      if (!state.navOpen) {
        $('#burger').attr('aria-expanded','true');
        if (state.searchOpen) {
          $('#searchtoggle').attr('aria-expanded','false');
          document.getElementById("site-search").blur();
          state.searchOpen = false;
          $('body').removeClass('search-active');
        }
      } else {
        if (state.subnavOpen) {
          state.subnavOpen = false;
          // document.querySelector('.menu-bar').classList.remove('subnav-active');
        }
        $('#burger').attr('aria-expanded','false');
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
