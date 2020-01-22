'use strict';

export function copyStringToClipboard (str) {
  // Create new element
  var el = document.createElement('textarea')
  // Set value (string to be copied)
  el.value = str
  // Set non-editable to avoid focus and move outside of view
  el.setAttribute('readonly', '')
  el.style = {position: 'absolute', left: '-9999px'}
  document.body.appendChild(el)
  // Select text inside element
  el.select()
  // Copy text to clipboard
  try {
    var successful = document.execCommand('copy')
    if (successful) {
      this.$root.$snackbar(this.$t('copied_to_clipboard'))
    } else {
      this.$root.$snackbar('Text could not be copied')
    }
  } catch (err) {
    this.$root.$snackbar('Text could not be copied')
  }
  // Remove temporary element
  document.body.removeChild(el)
}

export function strToSlug (str, separator = '-') {
  str = str.trim()
  str = str.toLowerCase()

  // remove accents, swap ñ for n, etc
  const from = "åàáãäâèéëêìíïîòóöôùúüûñç·/_,:;"
  const to = "aaaaaaeeeeiiiioooouuuunc------"

  for (let i = 0, l = from.length; i < l; i++) {
      str = str.replace(new RegExp(from.charAt(i), "g"), to.charAt(i))
  }

  return str
      .replace(/[^a-z0-9 -]/g, "") // remove invalid chars
      .replace(/\s+/g, "-") // collapse whitespace and replace by -
      .replace(/-+/g, "-") // collapse dashes
      .replace(/^-+/, "") // trim - from start of text
      .replace(/-+$/, "") // trim - from end of text
      .replace(/-/g, separator)
}

export function isBackgroundLight(hex) {
  /*
  Usage: let textcolor = getCorrectTextColor('#ff0000');

  From this W3C document: http://www.webmasterworld.com/r.cgi?f=88&d=9769&url=http://www.w3.org/TR/AERT#color-contrast

  Color brightness is determined by the following formula: 
  ((Red value X 299) + (Green value X 587) + (Blue value X 114)) / 1000

  I know this could be more compact, but I think this is easier to read/explain.
  */

  let threshold = 160; /* about half of 256. Lower threshold equals more dark text on dark background  */

  let hRed = hexToR(hex);
  let hGreen = hexToG(hex);
  let hBlue = hexToB(hex);

  function hexToR(h) {return parseInt((cutHex(h)).substring(0,2),16)}
  function hexToG(h) {return parseInt((cutHex(h)).substring(2,4),16)}
  function hexToB(h) {return parseInt((cutHex(h)).substring(4,6),16)}
  function cutHex(h) {return (h.charAt(0)=="#") ? h.substring(1,7):h}

  let cBrightness = ((hRed * 299) + (hGreen * 587) + (hBlue * 114)) / 1000;

  if (cBrightness > threshold) {
    return true;
  } else { 
    return false;
  }
}
