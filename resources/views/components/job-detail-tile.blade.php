@props(['icon', 'label', 'value', 'class' => ''])

<div {{ $attributes->merge(['class' => "col-md-4 col-sm-6 $class"]) }}>
    <div style="background: linear-gradient(135deg, #FFFFFF 0%, #FAFAFA 100%); border: 1px solid #E5E7EB; border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.04); cursor: pointer;"
         onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 20px rgba(244, 197, 66, 0.15)'; this.style.borderColor='#F4C542'"
         onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.04)'; this.style.borderColor='#E5E7EB'">
        
        <!-- Icon Container -->
        <div style="width: 60px; height: 60px; border-radius: 12px; background: linear-gradient(135deg, #F4C542 0%, #D4A017 100%); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
            <i class="bi {{ $icon }}" style="color: #0A0A0A; font-size: 1.5rem;"></i>
        </div>
        
        <!-- Label -->
        <div style="font-size: 0.8rem; color: #6B7280; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem;">
            {{ $label }}
        </div>
        
        <!-- Value -->
        <div style="font-size: 1.1rem; color: #0A0A0A; font-weight: 700;">
            {{ $value }}
        </div>
    </div>
</div>
