const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors');

module.exports = {
    mode: 'jit',
    purge: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                //body: ['Roboto'],
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                academy:{
                  primary: "#00b1eb",
                  secondary: "#00536f",
                  accent: "#d82d8a",
                },
                orange: colors.orange,
                gray: {
                  light: '#f5f5f5',
                  lightfa: '#fafafa',
                },
                dark: {
                  default: '#263238',
                  light: '#324144',
                  800: '#202b30',
                },
                black: {
                  000: '#000',
                  333: '#333',
                  666: '#666',
                },
                primary: {
                  300: '#64B5F6',
                  400: '#42A5F5',
                  500: '#2196F3',
                  600: '#1E88E5',
                  700: '#1976D2',
                  800: '#1565C0',
                },   
                secondary: {
                  300: '#9575CD',
                  400: '#7E57C2',
                  500: '#673AB7',
                  600: '#5E35B1',
                  700: '#512DA8',
                  800: '#4527A0',
                },
                warning: {
                  400: '#FF7043',
                  500: '#FF5722',
                  700: '#ec683e',
                },        
              },
              fontSize: {
                tiny: '.8125rem',
              },            
        },
        colors: {
          transparent: "transparent",
          current: "currentColor",
          black: "#000",
          white: "#fff",
          bluegray: colors.blueGray,
          // coolgray: colors.coolGray,
          gray: colors.gray,
          // truegray: colors.trueGray,
          // warmgray: colors.warmGray,
          red: colors.red,
          // orange: colors.orange,
          // amber: colors.amber,
          yellow: colors.yellow,
          // lime: colors.lime,
          green: colors.green,
          // emerald: colors.emerald,
          teal: colors.teal,
          cyan: colors.cyan,
          // lightblue: colors.lightBlue,
          blue: colors.blue,
          sky: colors.sky,
          indigo: colors.indigo,
          // violet: colors.violet,
          // purple: colors.purple,
          // fuchsia: colors.fuchsia,
          pink: colors.pink,
          // rose: colors.rose,
        },         
    },

    variants: {
        extend: {
            opacity: ['disabled'],
        },
    },

    daisyui: {
      themes: [
        {
          academy: {
            "primary":   "#00b1eb",
            "secondary": "#00536f",
            "accent":    "#d82d8a",
            "neutral":   "#01222f",
            "base-100":  "#FFFFFF",
            "info":      "#93E6FB",
            "success":   "#80CED1",
            "warning":   "#EFD8BD",
            "error":     "#E58B8B",
          },
        },
        'winter'
      ],
    },    

    plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography'), require("daisyui")],
};
