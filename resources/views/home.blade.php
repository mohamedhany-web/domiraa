@extends('layouts.app')

@section('title', 'منصة دوميرا - إيجار العقارات')

@push('styles')
<style>
    /* لوحة ألوان موحدة */
    :root {
        --primary: #1d313f;
        --primary-light: #2a4456;
        --primary-dark: #152431;
        --secondary: #6b8980;
        --secondary-light: #8aa69d;
        --secondary-dark: #536b63;
    }
    
    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, #FFFFFF 0%, rgba(107, 137, 128, 0.05) 50%, rgba(29, 49, 63, 0.05) 100%);
        position: relative;
        overflow: hidden;
        min-height: 85vh;
        display: flex;
        align-items: center;
        padding: 4rem 0 6rem;
    }
    
    @media (max-width: 768px) {
        .hero-section {
            min-height: 70vh;
            padding: 3rem 0 4rem;
        }
    }
    
    /* Animated Background - Enhanced */
    .animated-background {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        overflow: hidden;
        z-index: 0;
    }
    
    /* Floating Shapes - More shapes */
    .floating-shape {
        position: absolute;
        border-radius: 50%;
        opacity: 0.08;
        filter: blur(70px);
        will-change: transform;
        animation: float 25s infinite ease-in-out;
        pointer-events: none;
    }
    
    .shape-1 {
        width: 500px;
        height: 500px;
        background: var(--primary);
        top: -150px;
        right: -150px;
        animation-delay: 0s;
    }
    
    .shape-2 {
        width: 450px;
        height: 450px;
        background: var(--secondary);
        bottom: -120px;
        left: -120px;
        animation-delay: 3s;
    }
    
    .shape-3 {
        width: 400px;
        height: 400px;
        background: var(--primary-light);
        top: 30%;
        left: 10%;
        animation-delay: 6s;
    }
    
    .shape-4 {
        width: 350px;
        height: 350px;
        background: var(--secondary-light);
        bottom: 25%;
        right: 15%;
        animation-delay: 9s;
    }
    
    .shape-5 {
        width: 300px;
        height: 300px;
        background: var(--primary);
        top: 60%;
        right: 30%;
        animation-delay: 12s;
    }
    
    .shape-6 {
        width: 280px;
        height: 280px;
        background: var(--secondary);
        top: 15%;
        left: 40%;
        animation-delay: 15s;
    }
    
    /* Grid Pattern */
    .grid-pattern {
        position: absolute;
        width: 100%;
        height: 100%;
        background-image: 
            linear-gradient(rgba(29, 49, 63, 0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(29, 49, 63, 0.03) 1px, transparent 1px);
        background-size: 50px 50px;
        animation: grid-move 20s linear infinite;
        opacity: 0.5;
    }
    
    @keyframes grid-move {
        0% {
            transform: translate(0, 0);
        }
        100% {
            transform: translate(50px, 50px);
        }
    }
    
    /* Particles */
    .particles-container {
        position: absolute;
        width: 100%;
        height: 100%;
        overflow: hidden;
    }
    
    .particle {
        position: absolute;
        width: 4px;
        height: 4px;
        background: var(--primary);
        border-radius: 50%;
        opacity: 0.3;
        animation: particle-float 15s infinite ease-in-out;
        pointer-events: none;
    }
    
    @keyframes particle-float {
        0%, 100% {
            transform: translate(0, 0);
            opacity: 0.3;
        }
        50% {
            transform: translate(100px, -100px);
            opacity: 0.6;
        }
    }
    
    @keyframes float {
        0%, 100% {
            transform: translate(0, 0) scale(1) rotate(0deg);
        }
        25% {
            transform: translate(30px, -30px) scale(1.05) rotate(90deg);
        }
        50% {
            transform: translate(-20px, -60px) scale(0.95) rotate(180deg);
        }
        75% {
            transform: translate(-40px, 20px) scale(1.02) rotate(270deg);
        }
    }
    
    @media (prefers-reduced-motion: reduce) {
        .floating-shape,
        .grid-pattern,
        .particle {
            animation: none;
        }
    }
    
    .hero-content {
        position: relative;
        z-index: 10;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
    }
    
    .hero-title {
        font-size: 3.5rem;
        font-weight: 900;
        color: #1F2937;
        margin-bottom: 1.5rem;
        line-height: 1.2;
        animation: fadeInUp 1s ease-out;
    }
    
    .hero-subtitle {
        font-size: 1.4rem;
        color: #64748B;
        margin-bottom: 2.5rem;
        animation: fadeInUp 1.2s ease-out;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .cta-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        justify-content: center;
        animation: fadeInUp 1.4s ease-out;
    }
    
    @media (prefers-reduced-motion: reduce) {
        .hero-title,
        .hero-subtitle,
        .cta-buttons {
            animation: none;
        }
    }
    
    .btn-cta-primary {
        background: linear-gradient(135deg, var(--secondary) 0%, var(--secondary-dark) 100%);
        color: white;
        font-weight: 700;
        padding: 1.125rem 2.75rem;
        border-radius: 12px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        font-size: 1.1rem;
        box-shadow: 0 4px 15px rgba(107, 137, 128, 0.3);
        text-decoration: none;
    }
    
    .btn-cta-primary:hover {
        background: linear-gradient(135deg, var(--secondary-dark) 0%, var(--primary) 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(107, 137, 128, 0.4);
    }
    
    /* Filter Section */
    .filter-section {
        background: white;
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        margin: -3rem auto 3rem;
        max-width: 1200px;
        position: relative;
        z-index: 20;
        border: 1px solid #E5E7EB;
        backdrop-filter: blur(10px);
    }
    
    .filter-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary) 0%, var(--secondary) 100%);
        border-radius: 20px 20px 0 0;
    }
    
    .filter-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
    }
    
    .filter-group {
        display: flex;
        flex-direction: column;
    }
    
    .filter-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }
    
    .filter-input,
    .filter-select {
        padding: 0.875rem;
        border: 2px solid #E5E7EB;
        border-radius: 10px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }
    
    .filter-input:focus,
    .filter-select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(29, 49, 63, 0.1);
    }
    
    .filter-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
        justify-content: center;
    }
    
    .btn-filter {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
        font-weight: 700;
        padding: 0.875rem 2rem;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(29, 49, 63, 0.3);
    }
    
    .btn-reset {
        background: #F3F4F6;
        color: #374151;
        font-weight: 700;
        padding: 0.875rem 2rem;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-reset:hover {
        background: #E5E7EB;
    }
    
    /* Properties Section */
    .properties-section {
        padding: 5rem 0;
        background: linear-gradient(180deg, #FFFFFF 0%, #F8FAFC 100%);
        position: relative;
    }
    
    .properties-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: 
            radial-gradient(circle at 20% 50%, rgba(29, 49, 63, 0.03) 0%, transparent 50%),
            radial-gradient(circle at 80% 80%, rgba(107, 137, 128, 0.03) 0%, transparent 50%);
        pointer-events: none;
    }
    
    .section-header {
        text-align: center;
        margin-bottom: 3rem;
    }
    
    .section-title {
        font-size: 2.5rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 1rem;
    }
    
    .section-subtitle {
        font-size: 1.2rem;
        color: #64748B;
    }
    
    .properties-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 2rem;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
    }
    
    .property-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        border: 1px solid #E5E7EB;
    }
    
    .property-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }
    
    .property-image {
        height: 240px;
        overflow: hidden;
        background: linear-gradient(135deg, rgba(29, 49, 63, 0.1) 0%, rgba(107, 137, 128, 0.1) 100%);
        position: relative;
    }
    
    .property-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .property-card:hover .property-image img {
        transform: scale(1.1);
    }
    
    .property-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: rgba(255, 255, 255, 0.95);
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.75rem;
        backdrop-filter: blur(10px);
    }
    
    .property-badge.residential {
        color: var(--primary);
    }
    
    .property-badge.commercial {
        color: var(--secondary-dark);
    }
    
    .property-content {
        padding: 1.5rem;
    }
    
    .property-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 0.75rem;
    }
    
    .property-location {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #6B7280;
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }
    
    .property-price {
        font-size: 1.75rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 1rem;
    }
    
    .property-price span {
        font-size: 1rem;
        font-weight: 500;
        color: #64748B;
    }
    
    .btn-view {
        width: 100%;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
        font-weight: 700;
        padding: 0.875rem;
        border-radius: 10px;
        text-align: center;
        transition: all 0.3s ease;
        display: block;
        text-decoration: none;
    }
    
    .btn-view:hover {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--secondary-dark) 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(29, 49, 63, 0.3);
    }
    
    /* Features Section */
    .features-section {
        padding: 5rem 0;
        background: white;
        position: relative;
        overflow: hidden;
    }
    
    .features-section::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 1px;
        background: linear-gradient(90deg, transparent 0%, #E5E7EB 50%, transparent 100%);
    }
    
    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
    }
    
    .feature-card {
        background: linear-gradient(135deg, #F8FAFC 0%, #FFFFFF 100%);
        border-radius: 16px;
        padding: 2.5rem;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        border: 2px solid #E5E7EB;
    }
    
    .feature-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        border-color: var(--primary);
    }
    
    .feature-icon {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 2.5rem;
        transition: transform 0.3s ease;
        background: linear-gradient(135deg, rgba(29, 49, 63, 0.1) 0%, rgba(107, 137, 128, 0.15) 100%);
        color: var(--primary);
    }
    
    .feature-card:hover .feature-icon {
        transform: scale(1.1) rotate(5deg);
    }
    
    .feature-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 1rem;
    }
    
    .feature-text {
        color: #64748B;
        line-height: 1.7;
        font-size: 0.95rem;
    }
    
    /* Process Section */
    .process-section {
        padding: 5rem 0;
        background: linear-gradient(180deg, #F8FAFC 0%, #FFFFFF 100%);
        position: relative;
    }
    
    .process-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: 
            linear-gradient(rgba(29, 49, 63, 0.02) 1px, transparent 1px),
            linear-gradient(90deg, rgba(29, 49, 63, 0.02) 1px, transparent 1px);
        background-size: 60px 60px;
        opacity: 0.5;
        pointer-events: none;
    }
    
    .process-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
    }
    
    .process-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        border: 2px solid #E5E7EB;
        position: relative;
    }
    
    .process-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        border-color: var(--primary);
    }
    
    .process-number {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: 800;
        margin: 0 auto 1.5rem;
        box-shadow: 0 4px 15px rgba(29, 49, 63, 0.3);
        transition: transform 0.3s ease;
    }
    
    .process-card:hover .process-number {
        transform: scale(1.1) rotate(360deg);
    }
    
    .process-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 0.75rem;
    }
    
    .process-text {
        color: #64748B;
        font-size: 0.9rem;
        line-height: 1.6;
    }
    
    /* CTA Section */
    .cta-section {
        padding: 5rem 0;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        position: relative;
        overflow: hidden;
    }
    
    .cta-section::before {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        background-image: 
            radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 70% 70%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
        pointer-events: none;
    }
    
    .cta-section::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        border-radius: 50%;
        animation: pulse 4s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
            opacity: 0.5;
        }
        50% {
            transform: scale(1.1);
            opacity: 0.8;
        }
    }
    
    .cta-content {
        position: relative;
        z-index: 10;
        text-align: center;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
    }
    
    .cta-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: white;
        margin-bottom: 1rem;
    }
    
    .cta-text {
        font-size: 1.2rem;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 2rem;
    }
    
    .cta-buttons-inline {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .btn-cta-white {
        background: white;
        color: var(--primary);
        font-weight: 700;
        padding: 1rem 2.5rem;
        border-radius: 12px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        font-size: 1.1rem;
        text-decoration: none;
    }
    
    .btn-cta-white:hover {
        background: #F3F4F6;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
    }
    
    .btn-cta-outline {
        background: transparent;
        color: white;
        font-weight: 700;
        padding: 1rem 2.5rem;
        border-radius: 12px;
        border: 2px solid white;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        font-size: 1.1rem;
        text-decoration: none;
    }
    
    .btn-cta-outline:hover {
        background: white;
        color: var(--primary);
        transform: translateY(-2px);
    }
    
    /* Responsive - Enhanced */
    @media (max-width: 1024px) {
        .filter-section {
            margin: -2rem 1rem 2rem;
            padding: 2rem;
        }
        
        .hero-title {
            font-size: 2.75rem;
        }
        
        .hero-content {
            padding: 0 1.5rem;
        }
        
        .properties-grid {
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
        }
        
        .features-grid {
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        }
        
        .process-grid {
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        }
    }
    
    @media (max-width: 768px) {
        /* Ensure full width on mobile */
        .max-w-7xl {
            width: 100%;
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }
        
        .hero-section {
            min-height: 60vh;
            padding: 2rem 0 3rem;
        }
        
        .hero-title {
            font-size: 1.75rem;
            line-height: 1.3;
            margin-bottom: 1rem;
        }
        
        .hero-subtitle {
            font-size: 1rem;
            margin-bottom: 2rem;
            padding: 0 1rem;
        }
        
        .hero-content {
            padding: 0 1rem;
        }
        
        .cta-buttons {
            flex-direction: column;
            width: 100%;
            padding: 0 1rem;
            gap: 0.75rem;
        }
        
        .btn-cta-primary {
            width: 100%;
            justify-content: center;
            padding: 1rem 1.5rem;
            font-size: 1rem;
        }
        
        .filter-section {
            margin: -1.5rem 0.75rem 1.5rem;
            padding: 1.25rem;
            border-radius: 16px;
        }
        
        .filter-title {
            font-size: 1.125rem;
            margin-bottom: 1rem;
        }
        
        .filter-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .filter-group {
            margin-bottom: 0;
        }
        
        .filter-group[style*="grid-column"] {
            grid-column: span 1 !important;
        }
        
        .filter-label {
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }
        
        .filter-input,
        .filter-select {
            padding: 0.75rem;
            font-size: 0.9rem;
            border-radius: 8px;
        }
        
        .filter-buttons {
            flex-direction: column;
            width: 100%;
            margin-top: 1rem;
            gap: 0.75rem;
        }
        
        .btn-filter,
        .btn-reset {
            width: 100%;
            justify-content: center;
            padding: 0.875rem 1.5rem;
            font-size: 0.95rem;
        }
        
        /* Price slider mobile improvements */
        .price-range-group {
            grid-column: span 1 !important;
        }
        
        .price-slider-container {
            flex-direction: column !important;
            gap: 0.75rem !important;
            align-items: stretch !important;
        }
        
        .price-slider {
            width: 100%;
            margin: 0;
        }
        
        .price-display {
            min-width: auto !important;
            width: 100%;
            justify-content: center;
            padding: 0.5rem;
            background: #F9FAFB;
            border-radius: 8px;
        }
        
        .properties-section {
            padding: 3rem 0;
        }
        
        .properties-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
            padding: 0 1rem;
        }
        
        .property-card {
            border-radius: 12px;
        }
        
        .property-image {
            height: 200px;
        }
        
        .property-content {
            padding: 1.25rem;
        }
        
        .property-title {
            font-size: 1.1rem;
        }
        
        .property-price {
            font-size: 1.5rem;
        }
        
        .section-header {
            margin-bottom: 2rem;
            padding: 0 1rem;
        }
        
        .section-title {
            font-size: 1.75rem;
            margin-bottom: 0.75rem;
        }
        
        .section-subtitle {
            font-size: 1rem;
        }
        
        .features-section {
            padding: 3rem 0;
        }
        
        .features-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
            padding: 0 1rem;
        }
        
        .feature-card {
            padding: 2rem;
        }
        
        .feature-icon {
            width: 70px;
            height: 70px;
            font-size: 2rem;
        }
        
        .feature-title {
            font-size: 1.1rem;
        }
        
        .feature-text {
            font-size: 0.9rem;
        }
        
        .process-section {
            padding: 3rem 0;
        }
        
        .process-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
            padding: 0 1rem;
        }
        
        .process-card {
            padding: 1.5rem;
        }
        
        .process-number {
            width: 50px;
            height: 50px;
            font-size: 1.25rem;
        }
        
        .process-title {
            font-size: 1rem;
        }
        
        .process-text {
            font-size: 0.85rem;
        }
        
        .cta-section {
            padding: 3rem 0;
        }
        
        .cta-content {
            padding: 0 1rem;
        }
        
        .cta-title {
            font-size: 1.75rem;
            margin-bottom: 0.75rem;
        }
        
        .cta-text {
            font-size: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .cta-buttons-inline {
            flex-direction: column;
            width: 100%;
        }
        
        .btn-cta-white,
        .btn-cta-outline {
            width: 100%;
            justify-content: center;
            padding: 0.875rem 1.5rem;
            font-size: 1rem;
        }
        
        /* Floating shapes - smaller on mobile */
        .floating-shape {
            opacity: 0.05;
            filter: blur(50px);
        }
        
        .shape-1,
        .shape-2,
        .shape-3,
        .shape-4,
        .shape-5,
        .shape-6 {
            width: 200px;
            height: 200px;
        }
    }
    
    @media (max-width: 480px) {
        .hero-title {
            font-size: 1.5rem;
        }
        
        .hero-subtitle {
            font-size: 0.9rem;
        }
        
        .filter-section {
            margin: -1rem 0.5rem 1rem;
            padding: 1rem;
            border-radius: 12px;
        }
        
        .filter-title {
            font-size: 1rem;
            margin-bottom: 0.875rem;
        }
        
        .filter-grid {
            gap: 0.875rem;
        }
        
        .filter-input,
        .filter-select {
            padding: 0.625rem;
            font-size: 0.875rem;
        }
        
        .filter-label {
            font-size: 0.8rem;
        }
        
        .price-slider {
            height: 6px;
        }
        
        .price-slider::-webkit-slider-thumb {
            width: 18px;
            height: 18px;
        }
        
        .price-slider::-moz-range-thumb {
            width: 18px;
            height: 18px;
        }
        
        .section-title {
            font-size: 1.5rem;
        }
        
        .cta-title {
            font-size: 1.5rem;
        }
        
        .property-image {
            height: 180px;
        }
    }
    
    .price-slider {
        flex: 1;
        height: 8px;
        border-radius: 5px;
        background: #E5E7EB;
        outline: none;
        -webkit-appearance: none;
        cursor: pointer;
    }
    
    .price-slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: linear-gradient(135deg, #1d313f 0%, #6b8980 100%);
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        transition: transform 0.2s ease;
    }
    
    .price-slider::-webkit-slider-thumb:active {
        transform: scale(1.2);
    }
    
    .price-slider::-moz-range-thumb {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: linear-gradient(135deg, #1d313f 0%, #6b8980 100%);
        cursor: pointer;
        border: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        transition: transform 0.2s ease;
    }
    
    .price-slider::-moz-range-thumb:active {
        transform: scale(1.2);
    }
    
    /* Touch-friendly improvements for mobile */
    @media (max-width: 768px) {
        .filter-input,
        .filter-select {
            -webkit-appearance: none;
            appearance: none;
            touch-action: manipulation;
        }
        
        .filter-select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%231d313f' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: left 0.75rem center;
            padding-left: 2.5rem;
            padding-right: 0.75rem;
        }
        
        /* Prevent zoom on input focus (iOS) */
        .filter-input,
        .filter-select {
            font-size: 16px !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const searchAddress = document.getElementById('searchAddress');
    const searchStatus = document.getElementById('searchStatus');
    const searchPriceType = document.getElementById('searchPriceType');
    const priceRange = document.getElementById('priceRange');
    const priceRangeValue = document.getElementById('priceRangeValue');
    const maxPrice = document.getElementById('maxPrice');
    // Price slider - only update display, don't search
    if (priceRange) {
        priceRange.addEventListener('input', function() {
            const value = parseInt(this.value);
            priceRangeValue.textContent = new Intl.NumberFormat('ar-EG').format(value);
            maxPrice.value = value;
        });
        
        // Initialize price display
        const initialPrice = parseInt(priceRange.value);
        priceRangeValue.textContent = new Intl.NumberFormat('ar-EG').format(initialPrice);
    }
    
    // Form submission - perform search on button click
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            filterForm.submit();
        });
    }
});
</script>
@endpush

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="animated-background">
        <div class="floating-shape shape-1"></div>
        <div class="floating-shape shape-2"></div>
        <div class="floating-shape shape-3"></div>
        <div class="floating-shape shape-4"></div>
        <div class="floating-shape shape-5"></div>
        <div class="floating-shape shape-6"></div>
        <div class="grid-pattern"></div>
        <div class="particles-container" id="particles"></div>
    </div>
    
    <div class="hero-content text-center">
        <h1 class="hero-title">
            ابحث عن منزلك المثالي
        </h1>
        <p class="hero-subtitle">
            منصة موثوقة لإيجار العقارات السكنية والتجارية في جميع أنحاء مصر
        </p>
        <div class="cta-buttons">
            <a href="{{ route('properties') }}" class="btn-cta-primary">
                <i class="fas fa-building ml-2"></i>
                تصفح الوحدات
            </a>
            @auth
                @if(auth()->user()->role === 'owner')
                <a href="{{ route('owner.properties.create') }}" class="btn-cta-primary" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); box-shadow: 0 4px 15px rgba(29, 49, 63, 0.3);">
                    <i class="fas fa-plus ml-2"></i>
                    أضف وحدة
                </a>
                @else
                <a href="{{ route('register') }}" class="btn-cta-primary" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); box-shadow: 0 4px 15px rgba(29, 49, 63, 0.3);">
                    <i class="fas fa-upload ml-2"></i>
                    ارفع عقارك
                </a>
                @endif
            @else
            <a href="{{ route('register') }}" class="btn-cta-primary" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); box-shadow: 0 4px 15px rgba(29, 49, 63, 0.3);">
                <i class="fas fa-upload ml-2"></i>
                ارفع عقارك
            </a>
            @endauth
        </div>
    </div>
</section>

<!-- Filter Section -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" style="width: 100%;">
    <div class="filter-section">
        <h2 class="filter-title">
            <i class="fas fa-filter"></i>
            فلترة الوحدات
        </h2>
        
        <form method="GET" action="{{ route('home') }}" id="filterForm">
            <div class="filter-grid">
                <div class="filter-group">
                    <label class="filter-label">المنطقة / العنوان</label>
                    <input type="text" name="address" id="searchAddress" class="filter-input" placeholder="ابحث عن منطقة..." value="{{ request('address') }}">
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">نوع الوحدة</label>
                    <select name="property_type_id" id="searchPropertyType" class="filter-select">
                        <option value="">الكل</option>
                        @foreach(\App\Models\PropertyType::active() as $type)
                        <option value="{{ $type->id }}" {{ request('property_type_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">الحالة</label>
                    <select name="status" id="searchStatus" class="filter-select">
                        <option value="">الكل</option>
                        <option value="furnished" {{ request('status') == 'furnished' ? 'selected' : '' }}>مفروش</option>
                        <option value="unfurnished" {{ request('status') == 'unfurnished' ? 'selected' : '' }}>على البلاط</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">نوع السعر</label>
                    <select name="price_type" id="searchPriceType" class="filter-select">
                        <option value="">الكل</option>
                        <option value="daily" {{ request('price_type') == 'daily' ? 'selected' : '' }}>يومي</option>
                        <option value="monthly" {{ request('price_type') == 'monthly' ? 'selected' : '' }}>شهري</option>
                        <option value="yearly" {{ request('price_type') == 'yearly' ? 'selected' : '' }}>سنوي</option>
                    </select>
                </div>
                
                <div class="filter-group price-range-group" style="grid-column: span 2;">
                    <label class="filter-label">نطاق السعر</label>
                    <div class="price-slider-container" style="display: flex; align-items: center; gap: 1rem; margin-top: 0.5rem;">
                        <input type="range" name="price_range" id="priceRange" class="price-slider" min="0" max="100000" value="{{ request('price_range', 50000) }}" step="1000">
                        <div class="price-display" style="display: flex; align-items: center; gap: 0.5rem; min-width: 200px;">
                            <span id="priceRangeValue" style="font-weight: 700; color: #1d313f; font-size: 1.1rem;">{{ number_format(request('price_range', 50000)) }}</span>
                            <span style="color: #6B7280;">ج.م</span>
                        </div>
                    </div>
                    <input type="hidden" name="max_price" id="maxPrice" value="{{ request('max_price', 50000) }}">
                </div>
            </div>
            
            <div class="filter-buttons">
                <button type="submit" class="btn-filter">
                    <i class="fas fa-search"></i>
                    بحث
                </button>
                <a href="{{ route('home') }}" class="btn-reset">
                    <i class="fas fa-redo"></i>
                    إعادة تعيين
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Featured Properties -->
<section class="properties-section" style="position: relative; z-index: 1;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" style="position: relative; z-index: 10;">
        <div class="section-header">
            <h2 class="section-title">الوحدات المتاحة</h2>
            <p class="section-subtitle">اكتشف أفضل الوحدات المتاحة للإيجار في مختلف المناطق</p>
        </div>
        
        <div class="properties-grid" id="propertiesGrid">
            @forelse($featuredProperties as $property)
            <div class="property-card">
                <div class="property-image">
                    @if($property->images->first())
                        <img src="{{ $property->images->first()->thumbnail_url }}" 
                             data-src="{{ $property->images->first()->url }}"
                             alt="{{ $property->address }}"
                             loading="lazy"
                             onerror="this.onerror=null; this.src='/images/placeholder.svg';">
                    @else
                        <img src="/images/placeholder.svg" 
                             alt="{{ $property->address }}"
                             loading="lazy">
                    @endif
                    <div class="property-badge {{ $property->propertyType->slug ?? '' }}">
                        {{ $property->propertyType->name ?? 'غير محدد' }}
                    </div>
                </div>
                
                <div class="property-content">
                    <h3 class="property-title">{{ Str::limit($property->address, 40) }}</h3>
                    <div class="property-location">
                        <i class="fas fa-map-marker-alt" style="color: var(--primary);"></i>
                        <span>{{ Str::limit($property->address, 30) }}</span>
                    </div>
                    @if($property->is_room_rentable)
                    <div class="property-price" style="color: var(--primary); font-weight: 600;">
                        <i class="fas fa-users" style="margin-left: 0.5rem;"></i>
                        قابلة للمشاركة
                    </div>
                    @else
                    <div class="property-price">
                        {{ number_format($property->price) }}
                        <span>{{ $property->price_type === 'monthly' ? ' /شهر' : ($property->price_type === 'yearly' ? ' /سنة' : ' /يوم') }}</span>
                    </div>
                    @endif
                    <a href="{{ route('property.show', $property) }}" class="btn-view">
                        <i class="fas fa-eye ml-2"></i>
                        عرض التفاصيل
                    </a>
                </div>
            </div>
            @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 4rem 2rem;">
                <i class="fas fa-home" style="font-size: 4rem; color: #9CA3AF; margin-bottom: 1rem;"></i>
                <p style="font-size: 1.25rem; color: #6B7280; font-weight: 600;">لا توجد وحدات متاحة حالياً</p>
                <p style="color: #9CA3AF; margin-top: 0.5rem;">جرب تغيير معايير البحث</p>
            </div>
            @endforelse
        </div>
        
        @if($featuredProperties->count() > 0)
        <div style="text-align: center; margin-top: 3rem;">
            <a href="{{ route('properties') }}" 
               class="btn-cta-primary">
                <i class="fas fa-arrow-left ml-2"></i>
                عرض جميع الوحدات
            </a>
        </div>
        @endif
    </div>
</section>

<!-- Features Section -->
<section class="features-section" style="position: relative; z-index: 1;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" style="position: relative; z-index: 10;">
        <div class="section-header">
            <h2 class="section-title">لماذا تختار منصة دوميرا؟</h2>
            <p class="section-subtitle">نوفر لك أفضل تجربة في البحث عن الوحدات للإيجار</p>
        </div>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3 class="feature-title">آمن وموثوق</h3>
                <p class="feature-text">جميع الوحدات خاضعة لمراجعة دقيقة من قبل فريق متخصص لضمان الجودة والموثوقية</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h3 class="feature-title">بحث متقدم</h3>
                <p class="feature-text">ابحث بسهولة عن الوحدة المناسبة باستخدام فلاتر متقدمة حسب المنطقة والسعر والنوع</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h3 class="feature-title">حجز سريع</h3>
                <p class="feature-text">احجز موعد للمعاينة بسهولة وسرعة من خلال المنصة مع إمكانية الدفع الإلكتروني</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <h3 class="feature-title">دعم فني</h3>
                <p class="feature-text">فريق دعم فني متاح على مدار الساعة لمساعدتك في أي استفسار أو مشكلة</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-certificate"></i>
                </div>
                <h3 class="feature-title">وثائق معتمدة</h3>
                <p class="feature-text">جميع الوحدات مصحوبة بوثائق معتمدة وإثباتات ملكية موثقة</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h3 class="feature-title">سهولة الاستخدام</h3>
                <p class="feature-text">واجهة بسيطة وسهلة الاستخدام تعمل على جميع الأجهزة والأنظمة</p>
            </div>
        </div>
    </div>
</section>

<!-- Process Section -->
<section class="process-section" style="position: relative; z-index: 1;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" style="position: relative; z-index: 10;">
        <div class="section-header">
            <h2 class="section-title">كيف تعمل المنصة؟</h2>
            <p class="section-subtitle">خطوات بسيطة للعثور على منزلك المثالي</p>
        </div>
        
        <div class="process-grid">
            <div class="process-card">
                <div class="process-number">1</div>
                <h3 class="process-title">ابحث واختر</h3>
                <p class="process-text">استخدم فلاتر البحث المتقدمة للعثور على الوحدة المناسبة لك حسب المنطقة والسعر والنوع</p>
            </div>
            
            <div class="process-card">
                <div class="process-number">2</div>
                <h3 class="process-title">اطلع على التفاصيل</h3>
                <p class="process-text">تصفح الصور والفيديو والتفاصيل الكاملة للوحدة واتصل بالمالك مباشرة</p>
            </div>
            
            <div class="process-card">
                <div class="process-number">3</div>
                <h3 class="process-title">احجز المعاينة</h3>
                <p class="process-text">احجز موعد للمعاينة بسهولة وسرعة من خلال المنصة مع إمكانية الدفع الإلكتروني</p>
            </div>
            
            <div class="process-card">
                <div class="process-number">4</div>
                <h3 class="process-title">انتقل واستمتع</h3>
                <p class="process-text">بعد المعاينة والموافقة، اوقع العقد وانتقل لمنزلك الجديد واستمتع بالحياة</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="cta-content" style="position: relative; z-index: 10;">
        <h2 class="cta-title">جاهز للبدء في البحث عن منزلك المثالي؟</h2>
        <p class="cta-text">انضم الآن لآلاف المستخدمين الراضين عن خدماتنا</p>
        <div class="cta-buttons-inline">
            <a href="{{ route('properties') }}" class="btn-cta-white">
                <i class="fas fa-building ml-2"></i>
                ابدأ البحث الآن
            </a>
            @guest
            <a href="{{ route('register') }}" class="btn-cta-outline">
                <i class="fas fa-user-plus ml-2"></i>
                إنشاء حساب جديد
            </a>
            @endguest
        </div>
    </div>
</section>

<script>
// Create Particles Animation
document.addEventListener('DOMContentLoaded', function() {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    
    if (prefersReducedMotion) {
        return;
    }
    
    const particlesContainer = document.getElementById('particles');
    if (particlesContainer) {
        const particleCount = 40;
        
        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.top = Math.random() * 100 + '%';
            particle.style.animationDelay = Math.random() * 15 + 's';
            particle.style.animationDuration = (12 + Math.random() * 8) + 's';
            particlesContainer.appendChild(particle);
        }
    }
    
    // Filter Form - Update properties dynamically
    const filterForm = document.getElementById('filterForm');
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(filterForm);
            const params = new URLSearchParams();
            
            for (let [key, value] of formData.entries()) {
                if (value) {
                    params.append(key, value);
                }
            }
            
            // Reload page with filters
            window.location.href = '{{ route("home") }}?' + params.toString();
        });
    }
});
</script>
@endsection


