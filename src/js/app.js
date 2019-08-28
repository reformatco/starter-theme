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

const pageLoad = () => {
  if ($('.m--video .video').length) {
    $('.m--video .video').fitVids();
  }
}

export default pageLoad();