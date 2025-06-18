#!/bin/bash

# 🚄 Script de Deployment para Railway - Portal IT MSX International
# Este script automatiza la preparación del proyecto para Railway

echo "🚄 Portal IT MSX International - Preparación para Railway"
echo "=========================================================="

# Verificar si Git está inicializado
if [ ! -d ".git" ]; then
    echo "📦 Inicializando repositorio Git..."
    git init
    git add .
    git commit -m "Portal IT MSX International - Inicial"
    echo "✅ Repositorio Git inicializado"
else
    echo "✅ Repositorio Git ya existe"
fi

# Crear archivo de verificación
echo "🔍 Verificando archivos necesarios para Railway..."

required_files=(
    "railway.json"
    "Procfile" 
    "nixpacks.toml"
    "railway-setup.sql"
    "config.php"
    "index.php"
    "admin.php"
)

missing_files=()

for file in "${required_files[@]}"; do
    if [ -f "$file" ]; then
        echo "✅ $file - OK"
    else
        echo "❌ $file - FALTA"
        missing_files+=("$file")
    fi
done

if [ ${#missing_files[@]} -eq 0 ]; then
    echo ""
    echo "🎉 ¡Todos los archivos están listos para Railway!"
    echo ""
    echo "📋 PRÓXIMOS PASOS:"
    echo "=================="
    echo ""
    echo "1. 📤 Subir a GitHub:"
    echo "   - Ve a https://github.com y crea un nuevo repositorio"
    echo "   - Ejecuta estos comandos:"
    echo "     git remote add origin https://github.com/TU-USUARIO/portal-it-msx.git"
    echo "     git branch -M main"
    echo "     git push -u origin main"
    echo ""
    echo "2. 🚄 Deployment en Railway:"
    echo "   - Ve a https://railway.app"
    echo "   - Haz login con GitHub"
    echo "   - New Project → Deploy from GitHub repo"
    echo "   - Selecciona tu repositorio"
    echo ""
    echo "3. 🗄️ Configurar base de datos:"
    echo "   - En Railway: Add Service → Database → MySQL"
    echo "   - Ve a MySQL → Query"
    echo "   - Copia y pega el contenido de 'railway-setup.sql'"
    echo "   - Ejecuta el script"
    echo ""
    echo "4. 🎯 Acceder al portal:"
    echo "   - Usuario: admin"
    echo "   - Contraseña: password"
    echo ""
    echo "📚 Para más detalles, lee: RAILWAY-DEPLOYMENT.md"
    echo ""
else
    echo ""
    echo "❌ Faltan archivos. Por favor verifica la instalación."
fi

echo ""
echo "🔗 Enlaces útiles:"
echo "=================="
echo "• Railway: https://railway.app"
echo "• GitHub: https://github.com"
echo "• Documentación: README.md"
echo "• Guía detallada: RAILWAY-DEPLOYMENT.md" 