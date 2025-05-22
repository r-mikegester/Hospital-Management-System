/** @type {import('tailwindcss').Config} */
module.exports = {
    prefix: 'tw-', // Adds 'tw-' to all Tailwind classes
  content: [
    "./src/**/*.{html,js,php}", // Adjust this to match your file paths
    "./components/**/*.{html,js,php}", // Include all your components
  ],
  theme: {
    extend: {},
  },
  plugins: [require("daisyui")], // Add DaisyUI plugin
};
