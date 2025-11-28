/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: "class",   // ← tambahkan ini
  content: [
    "./*.{html,php,js}",
    "./**/*.{html,php,js}",
    "!./node_modules",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
