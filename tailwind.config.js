import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: ['class'],
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.{vue,js,ts,jsx,tsx}',
    ],
    theme: {
    	extend: {
    		fontFamily: {
    			sans: [
    				'Instrument Sans',
                    ...defaultTheme.fontFamily.sans
                ]
    		},
    		borderRadius: {
    			lg: 'var(--radius)',
    			md: 'calc(var(--radius) - 2px)',
    			sm: 'calc(var(--radius) - 4px)'
    		},
    		colors: {
    			background: 'var(--background)',
    			foreground: 'var(--foreground)',
    			card: {
    				DEFAULT: 'var(--card)',
    				foreground: 'var(--card-foreground)'
    			},
    			popover: {
    				DEFAULT: 'var(--popover)',
    				foreground: 'var(--popover-foreground)'
    			},
    			primary: {
    				DEFAULT: 'var(--primary)',
    				foreground: 'var(--primary-foreground)'
    			},
    			secondary: {
    				DEFAULT: 'var(--secondary)',
    				foreground: 'var(--secondary-foreground)'
    			},
    			muted: {
    				DEFAULT: 'var(--muted)',
    				foreground: 'var(--muted-foreground)'
    			},
    			accent: {
    				DEFAULT: 'var(--accent)',
    				foreground: 'var(--accent-foreground)',
    				primary: 'var(--accent-primary)',
    				'primary-light': 'var(--accent-primary-light)'
    			},
    			destructive: {
    				DEFAULT: 'var(--destructive)',
    				foreground: 'var(--destructive-foreground)'
    			},
    			border: 'var(--border)',
    			input: 'var(--input)',
    			ring: 'var(--ring)',
    			chart: {
    				'1': 'var(--chart-1)',
    				'2': 'var(--chart-2)',
    				'3': 'var(--chart-3)',
    				'4': 'var(--chart-4)',
    				'5': 'var(--chart-5)'
    			},
    			sidebar: {
    				DEFAULT: 'var(--sidebar-background)',
    				foreground: 'var(--sidebar-foreground)',
    				primary: 'var(--sidebar-primary)',
    				'primary-foreground': 'var(--sidebar-primary-foreground)',
    				accent: 'var(--sidebar-accent)',
    				'accent-foreground': 'var(--sidebar-accent-foreground)',
    				border: 'var(--sidebar-border)',
    				ring: 'var(--sidebar-ring)'
    			},
    			sys: {
    				bg: '#0B0F19',
    				card: '#131722',
    				border: '#2D3748',
    				purple: '#820ad1',
    				green: '#05c46b',
    				red: '#ff4d4d',
    				text: '#94A3B8'
    			},
    			real: {
    				bg: '#09090b',
    				card: '#111113',
    				greenCard: '#044e2e',
    				greenText: '#22c55e',
    				redText: '#ef4444',
    				iconPink: '#db2777',
    				iconPinkBg: '#500724',
    				iconGreen: '#65a30d',
    				iconGreenBg: '#14532d',
    				trackYellow: '#FFB800',
    				nuPurple: '#820AD1',
    				blueVault: '#0ea5e9'
    			}
    		}
    	}
    },
    plugins: [
        require('tailwindcss-animate'),
        // Plugin para adicionar variante light: (quando dark não está presente)
        function({ addVariant }) {
            addVariant('light', ':root:not(.dark) &');
        }
    ],
};
