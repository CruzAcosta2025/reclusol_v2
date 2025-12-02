import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },

            colors: {
                // ============================================
                // BRAND IDENTITY (RECLUSOL) - M Series
                // ============================================
                M1: "#22256e",          // Deep Navy - Primary Brand Color
                M2: "#1a1c56",          // Darker Navy - Brand Secondary
                M3: "#3a3d7f",          // Medium Navy - Brand Tertiary
                M4: "#4a4d8f",          // Light Navy - Brand Accent
                M5: "#5a5d9f",          // Lighter Navy - Brand Hover states
                M6: "#fcfdfd",         // Off-White - Brand Background

                // ============================================
                // ACCENT COLORS
                // ============================================
                accent: "#f6ae24",      // Gold/Amber - Primary Accent
                "accent-light": "#f6da98",     // Light Gold - Accent Hover
                "accent-dark": "#d98710",      // Dark Gold - Accent Active

                // ============================================
                // SEMANTIC STATUS COLORS
                // ============================================
                // Success / Approved
                success: "#10b981",            // Emerald Green
                "success-light": "#d1fae5",    // Light Green Background
                "success-dark": "#059669",     // Dark Green Hover

                // Error / Rejected / Cancelled
                error: "#ef4444",              // Red
                "error-light": "#fee2e2",      // Light Red Background
                "error-dark": "#dc2626",       // Dark Red Hover

                // Warning / Pending / Attention
                warning: "#f59e0b",            // Amber
                "warning-light": "#fef3c7",    // Light Amber Background
                "warning-dark": "#d97706",     // Dark Amber Hover

                // Info / Informational
                info: "#3b82f6",               // Blue
                "info-light": "#dbeafe",       // Light Blue Background
                "info-dark": "#1d4ed8",        // Dark Blue Hover

                // ============================================
                // SYSTEM STATUS ALTERNATIVE NAMING
                // ============================================
                approved: "#10b981",           // Alias for Success
                rejected: "#ef4444",           // Alias for Error
                pending: "#f59e0b",            // Alias for Warning
                paused: "#8b5cf6",             // Purple - Paused/Inactive
                "paused-light": "#ede9fe",     // Light Purple Background
                "paused-dark": "#7c3aed",      // Dark Purple Hover

                // ============================================
                // NEUTRAL PALETTE
                // ============================================
                neutral: "#dbdbde",            // Medium Gray (Original)
                "neutral-light": "#fcfdfd",    // Very Light Gray (Original)
                "neutral-lighter": "#f9fafb", // Almost White
                "neutral-lightest": "#f3f4f6",// Very Light Gray
                "neutral-dark": "#6b7280",     // Dark Gray
                "neutral-darker": "#4b5563",   // Darker Gray
                "neutral-darkest": "#1f2937",  // Nearly Black

                // ============================================
                // BACKGROUNDS & TEXT
                // ============================================
                "bg-primary": "#ffffff",       // Pure White
                "bg-secondary": "#f9fafb",     // Almost White
                "bg-tertiary": "#f3f4f6",      // Very Light Gray

                "text-primary": "#111827",     // Nearly Black - Main Text
                "text-secondary": "#4b5563",   // Medium Gray - Secondary Text
                "text-tertiary": "#9ca3af",    // Light Gray - Tertiary Text

                // ============================================
                // EXTENDED UTILITIES
                // ============================================
                border: "#e5e7eb",             // Light Gray for Borders
                "border-dark": "#d1d5db",      // Medium Gray for Borders
                shadow: "rgba(0, 0, 0, 0.1)",  // Default Shadow
            },
        },
    },

    plugins: [forms],
};
