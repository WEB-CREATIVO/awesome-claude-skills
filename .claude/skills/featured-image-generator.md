# featured-image-generator

Generate optimized featured images (JPG) with complete metadata for WordPress, ready to upload directly.

## Purpose

Create professional featured images for WordPress pages and posts in optimized JPG format with all necessary metadata, avoiding intermediate conversions or HTML-to-image workflows.

## When to use

Whenever the user requests a featured image for:
- A WordPress page
- A blog post / entrada
- Any content that needs a visual featured image
- Before: Do NOT generate HTML pages that need to be converted
- Instead: Generate JPG directly with metadata embedded

## Output format

```
featured-image-[context].jpg
├─ Format: JPG (optimized 85-90% quality)
├─ Size: ≤ 500KB
├─ Dimensions: 1200x800px (WordPress standard)
├─ Metadata embedded:
│  ├─ alt-text (descriptive, SEO, 125 chars max)
│  ├─ title (page/post title for image)
│  ├─ description (meta description, 160 chars max)
│  ├─ caption (leyenda/credit line)
│  └─ filename (slugified, descriptive)
└─ Ready to upload: No additional steps needed
```

## Process

1. **Design plan**: Define visual direction using brand/project colors, typography, layout
2. **Create visual**: Generate image using:
   - HTML Canvas → JPG export (using library/screenshot tool)
   - Python PIL/Pillow for programmatic generation
   - Or direct visual design tool
3. **Optimize**: Compress to ≤500KB at 1200x800px, 85-90% JPG quality
4. **Embed metadata**: Include:
   - Filename: `featured-image-[topic].jpg`
   - Alt text: Clear description
   - Title: Matches page/post title
   - Description: Meta description (160 chars)
   - Caption: Attribution or context line
5. **Deliver**: Single JPG file ready for WordPress Media upload

## Metadata template

```
Filename: featured-image-consultoria-ia-sierra-madrid.jpg
Alt-text: "Consultoría en inteligencia artificial en la Sierra de Madrid, mostrando conexiones tecnológicas sobre paisaje montañoso"
Title: "Consultoría IA • Sierra de Madrid"
Description: "Servicios especializados de consultoría e implementación de IA para empresas en la región Sierra de Madrid y Collado Villalba"
Caption: "Consultoría especializada en Inteligencia Artificial | IAEMPOWER"
```

## Tools available

- Python + PIL (Pillow) for programmatic image generation
- HTML Canvas with screenshot/export
- Design tool (Figma, Canva API if available)
- Image optimization: jpegoptim, imagemagick

## Color/design guidelines

Always reference the project's identity system:
- Check theme colors, typography, brand guidelines
- Use project's existing color palette (never generic defaults)
- Match existing visual direction
- Optimize for web readability at 1200x800px

## WordPress upload checklist

- [ ] File format: JPG
- [ ] Size: ≤500KB
- [ ] Dimensions: 1200x800px
- [ ] Alt-text filled
- [ ] Filename descriptive
- [ ] Ready to upload (no conversion needed)
