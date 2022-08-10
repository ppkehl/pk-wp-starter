// ################################################################################################################
// ##
// ##     COMMON JAVASCRIPT FUNCTIONS
// ##     Javascript functions  
// ##
// ################################################################################################################

//===============================================================================================================
// COMMON THEME FUNCTIONS
//===============================================================================================================

//------------------------------------------------------------------------------------------
// Remove no-js class
//------------------------------------------------------------------------------------------
document.documentElement.classList.remove('no-js');

//------------------------------------------------------------------------------------------
// Theme toggle button
//------------------------------------------------------------------------------------------
var themeToggleBtn = document.getElementById('theme-toggle');
if(themeToggleBtn){
  themeToggleBtn.addEventListener('click', function () {
      // if set via local storage previously
      if (localStorage.getItem('color-theme')) {
          if (localStorage.getItem('color-theme') === 'light') {
              document.documentElement.classList.add('dark');
              localStorage.setItem('color-theme', 'dark');
          } else {
              document.documentElement.classList.remove('dark');
              localStorage.setItem('color-theme', 'light');
          }

          // if NOT set via local storage previously
      } else {
          if (document.documentElement.classList.contains('dark')) {
              document.documentElement.classList.remove('dark');
              localStorage.setItem('color-theme', 'light');
          } else {
              document.documentElement.classList.add('dark');
              localStorage.setItem('color-theme', 'dark');
          }
      }
  });
}