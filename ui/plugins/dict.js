export default ({ app }, inject) => {
  const langs = {
    'en-US': 'English',
    'ru-RU': 'Русский'
  }
  let userLang = Object.keys(langs)[0]

  if (process.client) {
    if (window.navigator && langs[navigator.language]) {
      userLang = navigator.language
    }
    if (window.localStorage && langs[localStorage.lang]) {
      userLang = localStorage.lang
    }
  }

  const data = require(`~/assets/dict/${userLang}.json`)

  inject('dict', {
    langs () {
      return langs
    },
    getUserLang () {
      return userLang
    },
    setUserLang (code) {
      if (!langs[code]) {
        return false
      }

      if (process.client && window.localStorage) {
        localStorage.lang = code
      }

      userLang = code

      return true
    },
    tr (text, sect = 'main', vars = {}) {
      text = (data[sect] && data[sect][text]) ||
        (data.main && data.main[text]) ||
        text

      if (Object.keys(vars).length) {
        text = text.replace(
          /\$(\w+)/g, (str, name) => vars[name]
        )
      }

      return text
    },
    trError (text, vars = {}) {
      return this.tr(text, 'error', vars)
    }
  })
}
