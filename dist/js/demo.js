/**
 * AdminLTE Demo Menu
 * ------------------
 * You should not use this file in production.
 * This file is for demo purposes only.
 */

/* eslint-disable camelcase */

(function ($) {
  'use strict'

  function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1)
  }

  function createSkinBlock(colors, callback, noneSelected) {
    var $block = $('<select />', {
      class: noneSelected ? 'custom-select mb-3 border-0' : 'custom-select mb-3 text-light border-0 ' + colors[0].replace(/accent-|navbar-/, 'bg-')
    })

    if (noneSelected) {
      var $default = $('<option />', {
        text: 'None Selected'
      })

      $block.append($default)
    }

    colors.forEach(function (color) {
      var $color = $('<option />', {
        class: (typeof color === 'object' ? color.join(' ') : color).replace('navbar-', 'bg-').replace('accent-', 'bg-'),
        text: capitalizeFirstLetter((typeof color === 'object' ? color.join(' ') : color).replace(/navbar-|accent-|bg-/, '').replace('-', ' '))
      })

      $block.append($color)
    })
    if (callback) {
      $block.on('change', callback)
    }

    return $block
  }

  var $sidebar = $('.control-sidebar')
  var $container = $('<div />', {
    class: 'p-3 control-sidebar-content'
  })

  $sidebar.append($container)

  // Checkboxes

  $container.append(
    '<h5>Customize</h5><hr class="mb-2"/>'
  )

// Color Mode
var $dark_mode_checkbox = $('<input />', {
  type: 'checkbox',
  value: 1,
  checked: localStorage.getItem('dark-mode') === 'true',
  class: 'mr-1'
}).on('click', function () {
  if ($(this).is(':checked')) {
    $('body').addClass('dark-mode');
    localStorage.setItem('dark-mode', 'true');
  } else {
    $('body').removeClass('dark-mode');
    localStorage.setItem('dark-mode', 'false');
  }
});

if (localStorage.getItem('dark-mode') === 'true') {
  $('body').addClass('dark-mode');
}

var $dark_mode_container = $('<div />', { class: 'mb-4' }).append($dark_mode_checkbox).append('<span>Dark Mode</span>');
$container.append($dark_mode_container);

// Header Options
$container.append('<h6>Header Options</h6>');
var $header_fixed_checkbox = $('<input />', {
  type: 'checkbox',
  value: 1,
  checked: localStorage.getItem('layout-navbar-fixed') === 'true',
  class: 'mr-1'
}).on('click', function () {
  if ($(this).is(':checked')) {
    $('body').addClass('layout-navbar-fixed');
    localStorage.setItem('layout-navbar-fixed', 'true');
  } else {
    $('body').removeClass('layout-navbar-fixed');
    localStorage.setItem('layout-navbar-fixed', 'false');
  }
});

if (localStorage.getItem('layout-navbar-fixed') === 'true') {
  $('body').addClass('layout-navbar-fixed');
}

var $header_fixed_container = $('<div />', { class: 'mb-1' }).append($header_fixed_checkbox).append('<span>Fixed</span>');
$container.append($header_fixed_container);

var $dropdown_legacy_offset_checkbox = $('<input />', {
  type: 'checkbox',
  value: 1,
  checked: localStorage.getItem('dropdown-legacy') === 'true',
  class: 'mr-1'
}).on('click', function () {
  if ($(this).is(':checked')) {
    $('.main-header').addClass('dropdown-legacy');
    localStorage.setItem('dropdown-legacy', 'true');
  } else {
    $('.main-header').removeClass('dropdown-legacy');
    localStorage.setItem('dropdown-legacy', 'false');
  }
});

if (localStorage.getItem('dropdown-legacy') === 'true') {
  $('.main-header').addClass('dropdown-legacy');
}

var $dropdown_legacy_offset_container = $('<div />', { class: 'mb-1' }).append($dropdown_legacy_offset_checkbox).append('<span>Dropdown Legacy Offset</span>');
$container.append($dropdown_legacy_offset_container);

var $no_border_checkbox = $('<input />', {
  type: 'checkbox',
  value: 1,
  checked: localStorage.getItem('border-bottom-0') === 'true',
  class: 'mr-1'
}).on('click', function () {
  if ($(this).is(':checked')) {
    $('.main-header').addClass('border-bottom-0');
    localStorage.setItem('border-bottom-0', 'true');
  } else {
    $('.main-header').removeClass('border-bottom-0');
    localStorage.setItem('border-bottom-0', 'false');
  }
});

if (localStorage.getItem('border-bottom-0') === 'true') {
  $('.main-header').addClass('border-bottom-0');
}

var $no_border_container = $('<div />', { class: 'mb-4' }).append($no_border_checkbox).append('<span>No border</span>');
$container.append($no_border_container);
//end

// Sidebar Options
$container.append('<h6>Sidebar Options</h6>');

var $sidebar_collapsed_checkbox = $('<input />', {
  type: 'checkbox',
  value: 1,
  checked: localStorage.getItem('sidebar-collapse') === 'true',
  class: 'mr-1'
}).on('click', function () {
  if ($(this).is(':checked')) {
    $('body').addClass('sidebar-collapse');
    localStorage.setItem('sidebar-collapse', 'true');
    $(window).trigger('resize');
  } else {
    $('body').removeClass('sidebar-collapse');
    localStorage.setItem('sidebar-collapse', 'false');
    $(window).trigger('resize');
  }
});

if (localStorage.getItem('sidebar-collapse') === 'true') {
  $('body').addClass('sidebar-collapse');
  $(window).trigger('resize');
}
  //end

  var $sidebar_collapsed_container = $('<div />', { class: 'mb-1' }).append($sidebar_collapsed_checkbox).append('<span>Collapsed</span>')
$container.append($sidebar_collapsed_container)

$(document).on('collapsed.lte.pushmenu', '[data-widget="pushmenu"]', function () {
  $sidebar_collapsed_checkbox.prop('checked', true)
  localStorage.setItem('sidebar-collapsed', 'true')
})
$(document).on('shown.lte.pushmenu', '[data-widget="pushmenu"]', function () {
  $sidebar_collapsed_checkbox.prop('checked', false)
  localStorage.setItem('sidebar-collapsed', 'false')
})

if (localStorage.getItem('sidebar-collapsed') === 'true') {
  $('body').addClass('sidebar-collapse')
}

var $sidebar_fixed_checkbox = $('<input />', {
  type: 'checkbox',
  value: 1,
  checked: localStorage.getItem('layout-fixed') === 'true',
  class: 'mr-1'
}).on('click', function () {
  if ($(this).is(':checked')) {
    $('body').addClass('layout-fixed')
    localStorage.setItem('layout-fixed', 'true')
    $(window).trigger('resize')
  } else {
    $('body').removeClass('layout-fixed')
    localStorage.setItem('layout-fixed', 'false')
    $(window).trigger('resize')
  }
})

if (localStorage.getItem('layout-fixed') === 'true') {
  $('body').addClass('layout-fixed')
}

var $sidebar_fixed_container = $('<div />', { class: 'mb-1' }).append($sidebar_fixed_checkbox).append('<span>Fixed</span>')
$container.append($sidebar_fixed_container)

var $sidebar_mini_checkbox = $('<input />', {
  type: 'checkbox',
  value: 1,
  checked: localStorage.getItem('sidebar-mini') === 'true',
  class: 'mr-1'
}).on('click', function () {
  if ($(this).is(':checked')) {
    $('body').addClass('sidebar-mini')
    localStorage.setItem('sidebar-mini', 'true')
  } else {
    $('body').removeClass('sidebar-mini')
    localStorage.setItem('sidebar-mini', 'false')
  }
})

if (localStorage.getItem('sidebar-mini') === 'true') {
  $('body').addClass('sidebar-mini')
}

var $sidebar_mini_container = $('<div />', { class: 'mb-1' }).append($sidebar_mini_checkbox).append('<span>Sidebar Mini</span>')
$container.append($sidebar_mini_container)

var $sidebar_mini_md_checkbox = $('<input />', {
  type: 'checkbox',
  value: 1,
  checked: localStorage.getItem('sidebar-mini-md') === 'true',
  class: 'mr-1'
}).on('click', function () {
  if ($(this).is(':checked')) {
    $('body').addClass('sidebar-mini-md')
    localStorage.setItem('sidebar-mini-md', 'true')
  } else {
    $('body').removeClass('sidebar-mini-md')
    localStorage.setItem('sidebar-mini-md', 'false')
  }
})

if (localStorage.getItem('sidebar-mini-md') === 'true') {
  $('body').addClass('sidebar-mini-md')
}

var $sidebar_mini_md_container = $('<div />', { class: 'mb-1' }).append($sidebar_mini_md_checkbox).append('<span>Sidebar Mini MD</span>')
$container.append($sidebar_mini_md_container)

var $sidebar_mini_xs_checkbox = $('<input />', {
  type: 'checkbox',
  value: 1,
  checked: localStorage.getItem('sidebar-mini-xs') === 'true',
  class: 'mr-1'
}).on('click', function () {
  if ($(this).is(':checked')) {
    $('body').addClass('sidebar-mini-xs')
    localStorage.setItem('sidebar-mini-xs', 'true')
  } else {
    $('body').removeClass('sidebar-mini-xs')
    localStorage.setItem('sidebar-mini-xs', 'false')
  }
})

if (localStorage.getItem('sidebar-mini-xs') === 'true') {
  $('body').addClass('sidebar-mini-xs')
}

var $sidebar_mini_xs_container = $('<div />', { class: 'mb-1' }).append($sidebar_mini_xs_checkbox).append('<span>Sidebar Mini XS</span>')
$container.append($sidebar_mini_xs_container)

var $flat_sidebar_checkbox = $('<input />', {
  type: 'checkbox',
  value: 1,
  checked: localStorage.getItem('nav-flat') === 'true',
  class: 'mr-1'
}).on('click', function () {
  if ($(this).is(':checked')) {
    $('.nav-sidebar').addClass('nav-flat')
    localStorage.setItem('nav-flat', 'true')
  } else {
    $('.nav-sidebar').removeClass('nav-flat')
    localStorage.setItem('nav-flat', 'false')
  }
})

if (localStorage.getItem('nav-flat') === 'true') {
  $('.nav-sidebar').addClass('nav-flat')
}

var $flat_sidebar_container = $('<div />', { class: 'mb-1' }).append($flat_sidebar_checkbox).append('<span>Nav Flat Style</span>')
$container.append($flat_sidebar_container)

var $legacy_sidebar_checkbox = $('<input />', {
  type: 'checkbox',
  value: 1,
  checked: localStorage.getItem('nav-legacy') === 'true',
  class: 'mr-1'
}).on('click', function () {
  if ($(this).is(':checked')) {
    $('.nav-sidebar').addClass('nav-legacy')
    localStorage.setItem('nav-legacy', 'true')
  } else {
    $('.nav-sidebar').removeClass('nav-legacy')
    localStorage.setItem('nav-legacy', 'false')
  }
})

if (localStorage.getItem('nav-legacy') === 'true') {
  $('.nav-sidebar').addClass('nav-legacy')
}

var $legacy_sidebar_container = $('<div />', { class: 'mb-1' }).append($legacy_sidebar_checkbox).append('<span>Nav Legacy Style</span>')
$container.append($legacy_sidebar_container)

var $compact_sidebar_checkbox = $('<input />', {
  type: 'checkbox',
  value: 1,
  checked: localStorage.getItem('nav-compact') === 'true',
  class: 'mr-1'
}).on('click', function () {
  if ($(this).is(':checked')) {
    $('.nav-sidebar').addClass('nav-compact')
    localStorage.setItem('nav-compact', 'true')
  } else {
    $('.nav-sidebar').removeClass('nav-compact')
    localStorage.setItem('nav-compact', 'false')
  }
})

if (localStorage.getItem('nav-compact') === 'true') {
  $('.nav-sidebar').addClass('nav-compact')
}

var $compact_sidebar_container = $('<div />', { class: 'mb-1' }).append($compact_sidebar_checkbox).append('<span>Nav Compact</span>')
$container.append($compact_sidebar_container)

var $child_indent_sidebar_checkbox = $('<input />', {
  type: 'checkbox',
  value: 1,
  checked: localStorage.getItem('nav-child-indent') === 'true',
  class: 'mr-1'
}).on('click', function () {
  if ($(this).is(':checked')) {
    $('.nav-sidebar').addClass('nav-child-indent')
    localStorage.setItem('nav-child-indent', 'true')
  } else {
    $('.nav-sidebar').removeClass('nav-child-indent')
    localStorage.setItem('nav-child-indent', 'false')
  }
})

if (localStorage.getItem('nav-child-indent') === 'true') {
  $('.nav-sidebar').addClass('nav-child-indent')
}

var $child_indent_sidebar_container = $('<div />', { class: 'mb-1' }).append($child_indent_sidebar_checkbox).append('<span>Nav Child Indent</span>')
$container.append($child_indent_sidebar_container)

var $child_hide_sidebar_checkbox = $('<input />', {
  type: 'checkbox',
  value: 1,
  checked: localStorage.getItem('nav-collapse-hide-child') === 'true',
  class: 'mr-1'
}).on('click', function () {
  if ($(this).is(':checked')) {
    $('.nav-sidebar').addClass('nav-collapse-hide-child')
    localStorage.setItem('nav-collapse-hide-child', 'true')
  } else {
    $('.nav-sidebar').removeClass('nav-collapse-hide-child')
    localStorage.setItem('nav-collapse-hide-child', 'false')
  }
})

if (localStorage.getItem('nav-collapse-hide-child') === 'true') {
  $('.nav-sidebar').addClass('nav-collapse-hide-child')
}

var $child_hide_sidebar_container = $('<div />', { class: 'mb-1' }).append($child_hide_sidebar_checkbox).append('<span>Nav Child Hide on Collapse</span>')
$container.append($child_hide_sidebar_container)

var $no_expand_sidebar_checkbox = $('<input />', {
  type: 'checkbox',
  value: 1,
  checked: localStorage.getItem('sidebar-no-expand') === 'true',
  class: 'mr-1'
}).on('click', function () {
  if ($(this).is(':checked')) {
    $('.main-sidebar').addClass('sidebar-no-expand')
    localStorage.setItem('sidebar-no-expand', 'true')
  } else {
    $('.main-sidebar').removeClass('sidebar-no-expand')
    localStorage.setItem('sidebar-no-expand', 'false')
  }
})

if (localStorage.getItem('sidebar-no-expand') === 'true') {
  $('.main-sidebar').addClass('sidebar-no-expand')
}
//end

//start
var $no_expand_sidebar_container = $('<div />', { class: 'mb-4' }).append($no_expand_sidebar_checkbox).append('<span>Disable Hover/Focus Auto-Expand</span>')
$container.append($no_expand_sidebar_container)

if (localStorage.getItem('sidebar-no-expand') === 'true') {
  $('.main-sidebar').addClass('sidebar-no-expand')
}

$container.append('<h6>Footer Options</h6>')
var $footer_fixed_checkbox = $('<input />', {
  type: 'checkbox',
  value: 1,
  checked: localStorage.getItem('layout-footer-fixed') === 'true',
  class: 'mr-1'
}).on('click', function () {
  if ($(this).is(':checked')) {
    $('body').addClass('layout-footer-fixed')
    localStorage.setItem('layout-footer-fixed', 'true')
  } else {
    $('body').removeClass('layout-footer-fixed')
    localStorage.setItem('layout-footer-fixed', 'false')
  }
})

if (localStorage.getItem('layout-footer-fixed') === 'true') {
  $('body').addClass('layout-footer-fixed')
}

var $footer_fixed_container = $('<div />', { class: 'mb-4' }).append($footer_fixed_checkbox).append('<span>Fixed</span>')
$container.append($footer_fixed_container)

$container.append('<h6>Small Text Options</h6>')

var $text_sm_body_checkbox = $('<input />', {
  type: 'checkbox',
  value: 1,
  checked: localStorage.getItem('text-sm-body') === 'true',
  class: 'mr-1'
}).on('click', function () {
  if ($(this).is(':checked')) {
    $('body').addClass('text-sm')
    localStorage.setItem('text-sm-body', 'true')
  } else {
    $('body').removeClass('text-sm')
    localStorage.setItem('text-sm-body', 'false')
  }
})

if (localStorage.getItem('text-sm-body') === 'true') {
  $('body').addClass('text-sm')
}

var $text_sm_body_container = $('<div />', { class: 'mb-1' }).append($text_sm_body_checkbox).append('<span>Body</span>')
$container.append($text_sm_body_container)

var $text_sm_header_checkbox = $('<input />', {
  type: 'checkbox',
  value: 1,
  checked: localStorage.getItem('text-sm-header') === 'true',
  class: 'mr-1'
}).on('click', function () {
  if ($(this).is(':checked')) {
    $('.main-header').addClass('text-sm')
    localStorage.setItem('text-sm-header', 'true')
  } else {
    $('.main-header').removeClass('text-sm')
    localStorage.setItem('text-sm-header', 'false')
  }
})

if (localStorage.getItem('text-sm-header') === 'true') {
  $('.main-header').addClass('text-sm')
}

var $text_sm_header_container = $('<div />', { class: 'mb-1' }).append($text_sm_header_checkbox).append('<span>Navbar</span>')
$container.append($text_sm_header_container)

var $text_sm_brand_checkbox = $('<input />', {
  type: 'checkbox',
  value: 1,
  checked: localStorage.getItem('text-sm-brand') === 'true',
  class: 'mr-1'
}).on('click', function () {
  if ($(this).is(':checked')) {
    $('.brand-link').addClass('text-sm')
    localStorage.setItem('text-sm-brand', 'true')
  } else {
    $('.brand-link').removeClass('text-sm')
    localStorage.setItem('text-sm-brand', 'false')
  }
})

if (localStorage.getItem('text-sm-brand') === 'true') {
  $('.brand-link').addClass('text-sm')
}

var $text_sm_brand_container = $('<div />', { class: 'mb-1' }).append($text_sm_brand_checkbox).append('<span>Brand</span>')
$container.append($text_sm_brand_container)

var $text_sm_sidebar_checkbox = $('<input />', {
  type: 'checkbox',
  value: 1,
  checked: localStorage.getItem('text-sm-sidebar') === 'true',
  class: 'mr-1'
}).on('click', function () {
  if ($(this).is(':checked')) {
    $('.nav-sidebar').addClass('text-sm')
    localStorage.setItem('text-sm-sidebar', 'true')
  } else {
    $('.nav-sidebar').removeClass('text-sm')
    localStorage.setItem('text-sm-sidebar', 'false')
  }
})

if (localStorage.getItem('text-sm-sidebar') === 'true') {
  $('.nav-sidebar').addClass('text-sm')
}

var $text_sm_sidebar_container = $('<div />', { class: 'mb-1' }).append($text_sm_sidebar_checkbox).append('<span>Sidebar Nav</span>')
$container.append($text_sm_sidebar_container)

var $text_sm_footer_checkbox = $('<input />', {
  type: 'checkbox',
  value: 1,
  checked: localStorage.getItem('text-sm-footer') === 'true',
  class: 'mr-1'
}).on('click', function () {
  if ($(this).is(':checked')) {
    $('.main-footer').addClass('text-sm')
    localStorage.setItem('text-sm-footer', 'true')
  } else {
    $('.main-footer').removeClass('text-sm')
    localStorage.setItem('text-sm-footer', 'false')
  }
})

if (localStorage.getItem('text-sm-footer') === 'true') {
  $('.main-footer').addClass('text-sm')
}

var $text_sm_footer_container = $('<div />', { class: 'mb-1' }).append($text_sm_footer_checkbox).append('<span>Footer</span>')
$container.append($text_sm_footer_container)

//end

var $text_sm_footer_container = $('<div />', { class: 'mb-4' }).append($text_sm_footer_checkbox).append('<span>Footer</span>')
$container.append($text_sm_footer_container)

// Color Arrays

var navbar_dark_skins = [
  'navbar-primary',
  'navbar-secondary',
  'navbar-info',
  'navbar-success',
  'navbar-danger',
  'navbar-indigo',
  'navbar-purple',
  'navbar-pink',
  'navbar-navy',
  'navbar-lightblue',
  'navbar-teal',
  'navbar-cyan',
  'navbar-dark',
  'navbar-gray-dark',
  'navbar-gray'
]

var navbar_light_skins = [
  'navbar-light',
  'navbar-warning',
  'navbar-white',
  'navbar-orange'
]

var sidebar_colors = [
  'bg-primary',
  'bg-warning',
  'bg-info',
  'bg-danger',
  'bg-success',
  'bg-indigo',
  'bg-lightblue',
  'bg-navy',
  'bg-purple',
  'bg-fuchsia',
  'bg-pink',
  'bg-maroon',
  'bg-orange',
  'bg-lime',
  'bg-teal',
  'bg-olive'
]

var accent_colors = [
  'accent-primary',
  'accent-warning',
  'accent-info',
  'accent-danger',
  'accent-success',
  'accent-indigo', 
  'accent-lightblue',
  'accent-navy',
  'accent-purple',
  'accent-fuchsia',
  'accent-pink',
  'accent-maroon',
  'accent-orange',
  'accent-lime',
  'accent-teal',
  'accent-olive'
]

var sidebar_skins = [
  'sidebar-dark-primary',
  'sidebar-dark-warning',
  'sidebar-dark-info',
  'sidebar-dark-danger',
  'sidebar-dark-success',
  'sidebar-dark-indigo',
  'sidebar-dark-lightblue',
  'sidebar-dark-navy',
  'sidebar-dark-purple',
  'sidebar-dark-fuchsia',
  'sidebar-dark-pink',
  'sidebar-dark-maroon',
  'sidebar-dark-orange',
  'sidebar-dark-lime',
  'sidebar-dark-teal',
  'sidebar-dark-olive',
  'sidebar-light-primary',
  'sidebar-light-warning',
  'sidebar-light-info',
  'sidebar-light-danger',
  'sidebar-light-success',
  'sidebar-light-indigo',
  'sidebar-light-lightblue',
  'sidebar-light-navy',
  'sidebar-light-purple',
  'sidebar-light-fuchsia',
  'sidebar-light-pink',
  'sidebar-light-maroon',
  'sidebar-light-orange',
  'sidebar-light-lime',
  'sidebar-light-teal',
  'sidebar-light-olive'
]

// Navbar Variants

$container.append('<h6>Navbar Variants</h6>')

var $navbar_variants = $('<div />', {
  class: 'd-flex'
})

// Save selected navbar variant to localStorage
function saveNavbarVariant(variant) {
  localStorage.setItem('navbar-variant', variant)
}

// Load navbar variant from localStorage
function loadNavbarVariant() {
  var variant = localStorage.getItem('navbar-variant')
  if (variant) {
    $('.main-header').addClass(variant)
  }
}

loadNavbarVariant()

// Generate navbar variant buttons
navbar_dark_skins.concat(navbar_light_skins).forEach(function (skin) {
  var $color = $('<div />', { class: skin + ' elevation-2' })
  $color.css({
    width: '40px',
    height: '20px',
    cursor: 'pointer',
    margin: '5px'
  }).on('click', function () {
    $('.main-header').removeClass(navbar_dark_skins.concat(navbar_light_skins).join(' '))
    $('.main-header').addClass(skin)
    saveNavbarVariant(skin)
  })

  $navbar_variants.append($color)
})

$container.append($navbar_variants)

// Sidebar Colors

$container.append('<h6>Accent Color Variants</h6>')
var $accent_variants = $('<div />', {
  class: 'd-flex'
})
$container.append($accent_variants)
$container.append(createSkinBlock(accent_colors, function () {
  var color = $(this).find('option:selected').attr('class')
  var $body = $('body')
  accent_colors.forEach(function (skin) {
    $body.removeClass(skin)
  })

  var accent_color_class = color.replace('bg-', 'accent-')
  $body.addClass(accent_color_class)
  saveAccentVariant(accent_color_class)
}, true))

function saveAccentVariant(variant) {
  localStorage.setItem('accent-variant', variant)
}

function loadAccentVariant() {
  var variant = localStorage.getItem('accent-variant')
  if (variant) {
    $('body').addClass(variant)
  }
}

loadAccentVariant()

$container.append('<h6>Dark Sidebar Variants</h6>')
var $sidebar_variants_dark = $('<div />', {
  class: 'd-flex'
})
$container.append($sidebar_variants_dark)
var $sidebar_dark_variants = createSkinBlock(sidebar_colors, function () {
  var color = $(this).find('option:selected').attr('class')
  var sidebar_class = 'sidebar-dark-' + color.replace('bg-', '')
  var $sidebar = $('.main-sidebar')
  sidebar_skins.forEach(function (skin) {
    $sidebar.removeClass(skin)
    $sidebar_light_variants.removeClass(skin.replace('sidebar-dark-', 'bg-')).removeClass('text-light')
  })

  $(this).removeClass().addClass('custom-select mb-3 text-light border-0').addClass(color)

  $sidebar_light_variants.find('option').prop('selected', false)
  $sidebar.addClass(sidebar_class)
  $('.sidebar').removeClass('os-theme-dark').addClass('os-theme-light')
  saveSidebarVariant(sidebar_class)
}, true)
$container.append($sidebar_dark_variants)

function saveSidebarVariant(variant) {
  localStorage.setItem('sidebar-variant', variant)
}

function loadSidebarVariant() {
  var variant = localStorage.getItem('sidebar-variant')
  if (variant) {
    $('.main-sidebar').addClass(variant)
  }
}

loadSidebarVariant()

var active_sidebar_dark_color = null
$('.main-sidebar')[0].classList.forEach(function (className) {
  var color = className.replace('sidebar-dark-', 'bg-')
  if (sidebar_colors.indexOf(color) > -1 && active_sidebar_dark_color === null) {
    active_sidebar_dark_color = color
  }
})

$sidebar_dark_variants.find('option.' + active_sidebar_dark_color).prop('selected', true)
$sidebar_dark_variants.removeClass().addClass('custom-select mb-3 text-light border-0 ').addClass(active_sidebar_dark_color)

$container.append('<h6>Light Sidebar Variants</h6>')
var $sidebar_variants_light = $('<div />', {
  class: 'd-flex'
})
$container.append($sidebar_variants_light)
var $sidebar_light_variants = createSkinBlock(sidebar_colors, function () {
  var color = $(this).find('option:selected').attr('class')
  var sidebar_class = 'sidebar-light-' + color.replace('bg-', '')
  var $sidebar = $('.main-sidebar')
  sidebar_skins.forEach(function (skin) {
    $sidebar.removeClass(skin)
    $sidebar_dark_variants.removeClass(skin.replace('sidebar-light-', 'bg-')).removeClass('text-light')
  })

  $(this).removeClass().addClass('custom-select mb-3 text-light border-0').addClass(color)

  $sidebar_dark_variants.find('option').prop('selected', false)
  $sidebar.addClass(sidebar_class)
  $('.sidebar').removeClass('os-theme-light').addClass('os-theme-dark')
  saveSidebarVariant(sidebar_class)
}, true)
$container.append($sidebar_light_variants)

var active_sidebar_light_color = null
$('.main-sidebar')[0].classList.forEach(function (className) {
  var color = className.replace('sidebar-light-', 'bg-')
  if (sidebar_colors.indexOf(color) > -1 && active_sidebar_light_color === null) {
    active_sidebar_light_color = color
  }
})

if (active_sidebar_light_color !== null) {
  $sidebar_light_variants.find('option.' + active_sidebar_light_color).prop('selected', true)
  $sidebar_light_variants.removeClass().addClass('custom-select mb-3 text-light border-0 ').addClass(active_sidebar_light_color)
}

var logo_skins = navbar_dark_skins.concat(navbar_light_skins)
$container.append('<h6>Brand Logo Variants</h6>')
var $logo_variants = $('<div />', {
  class: 'd-flex'
})
$container.append($logo_variants)
var $clear_btn = $('<a />', {
  href: '#'
}).text('clear').on('click', function (e) {
  e.preventDefault()
  var $logo = $('.brand-link')
  logo_skins.forEach(function (skin) {
    $logo.removeClass(skin)
  })
  localStorage.removeItem('logo-variant')
})

var $brand_variants = createSkinBlock(logo_skins, function () {
  var color = $(this).find('option:selected').attr('class')
  var $logo = $('.brand-link')

  if (color === 'navbar-light' || color === 'navbar-white') {
    $logo.addClass('text-black')
  } else {
    $logo.removeClass('text-black')
  }

  logo_skins.forEach(function (skin) {
    $logo.removeClass(skin)
  })

  if (color) {
    $(this).removeClass().addClass('custom-select mb-3 border-0').addClass(color).addClass(color !== 'navbar-light' && color !== 'navbar-white' ? 'text-light' : '')
  } else {
    $(this).removeClass().addClass('custom-select mb-3 border-0')
  }

  $logo.addClass(color)
  saveLogoVariant(color)
}, true).append($clear_btn)
$container.append($brand_variants)

function saveLogoVariant(variant) {
  localStorage.setItem('logo-variant', variant)
}

function loadLogoVariant() {
  var variant = localStorage.getItem('logo-variant')
  if (variant) {
    var $logo = $('.brand-link')
    $logo.addClass(variant)
    if (variant === 'navbar-light' || variant === 'navbar-white') {
      $logo.addClass('text-black')
    } else {
      $logo.removeClass('text-black')
    }
  }
}

loadLogoVariant()

var active_brand_color = null
$('.brand-link')[0].classList.forEach(function (className) {
  if (logo_skins.indexOf(className) > -1 && active_brand_color === null) {
    active_brand_color = className.replace('navbar-', 'bg-')
  }
})

if (active_brand_color) {
  $brand_variants.find('option.' + active_brand_color).prop('selected', true)
  $brand_variants.removeClass().addClass('custom-select mb-3 text-light border-0 ').addClass(active_brand_color)
}
})(jQuery)