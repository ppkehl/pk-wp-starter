// ################################################################################################################
// ##
// ##     DOM MANIPULATION JAVASCRIPT FUNCTIONS
// ##     Javascript functions  
// ##
// ################################################################################################################

//------------------------------------------------------------------------------------------
// Add Global Event Listener
//------------------------------------------------------------------------------------------

export function addGlobalEventListener(
  type,
  selector,
  callback,
  options,
  parent = document
) {
  parent.addEventListener(
    type,
    e => {
      if (e.target.matches(selector)) callback(e)
    },
    options
  )
}

//------------------------------------------------------------------------------------------
// Query selector shortcut
//------------------------------------------------------------------------------------------

export function qs(selector, parent = document) {
  return parent.querySelector(selector)
}

//------------------------------------------------------------------------------------------
// Query selector ALL shortcut
//------------------------------------------------------------------------------------------

export function qsa(selector, parent = document) {
  return [...parent.querySelectorAll(selector)]
}

//------------------------------------------------------------------------------------------
// Create element shortcut
//------------------------------------------------------------------------------------------

export function createElement(type, options = {}) {
  const element = document.createElement(type)
  Object.entries(options).forEach(([key, value]) => {
    if (key === "class") {
      element.classList.add(value)
      return
    }

    if (key === "dataset") {
      Object.entries(value).forEach(([dataKey, dataValue]) => {
        element.dataset[dataKey] = dataValue
      })
      return
    }

    if (key === "text") {
      element.textContent = value
      return
    }

    element.setAttribute(key, value)
  })
  return element
}