# 🚀 Guía para Preparar y Subir GYMA a GitHub

Esta guía te ayudará a preparar tu proyecto GYMA y subirlo correctamente a GitHub.

## ✅ Checklist de Seguridad

Antes de subir el proyecto, verifica que:

- [ ] El archivo `.env` NO está en el repositorio (debe estar en .gitignore)
- [ ] No hay archivos `.sql` con datos reales
- [ ] No hay credenciales o contraseñas en el código
- [ ] No hay claves API expuestas
- [ ] Los archivos temporales están excluidos en .gitignore
- [ ] El archivo `.env.example` tiene valores de ejemplo (no reales)

## 📋 Pasos para Preparar el Repositorio

### 1. Verificar Archivos Sensibles

Ejecuta estos comandos para verificar que no haya archivos sensibles:

```bash
# Verificar si .env está trackeado (NO debería aparecer)
git ls-files | findstr .env

# Verificar archivos SQL (NO deberían aparecer)
git ls-files | findstr .sql

# Ver todos los archivos trackeados
git ls-files
```

### 2. Limpiar Archivos Temporales

Si tienes archivos temporales que no quieres subir:

```bash
# Eliminar archivos de correcciones (ya están en .gitignore)
del CORRECCIONES_APLICADAS.txt
del CAMBIOS_REALIZADOS.md
del limpiar_cache_bcv.bat
del COMANDOS_UTILES.md
```

### 3. Verificar .gitignore

Asegúrate de que tu `.gitignore` incluya:

```gitignore
# Archivos sensibles
.env
.env.backup
.env.production

# Base de datos
*.sql
*.sqlite
*.db

# Backups
*.backup
*.bak

# Dependencias
/vendor
/node_modules

# Archivos temporales
CORRECCIONES_APLICADAS.txt
CAMBIOS_REALIZADOS.md
*.bat
```

### 4. Inicializar Git (si no está inicializado)

```bash
# Inicializar repositorio
git init

# Agregar todos los archivos
git add .

# Hacer el primer commit
git commit -m "Initial commit: GYMA - Sistema de Gestión de Gimnasio"
```

### 5. Conectar con GitHub

#### Opción A: Repositorio Nuevo

Si el repositorio en GitHub está vacío:

```bash
# Agregar el remote
git remote add origin https://github.com/Adrianmbt/gyma.git

# Subir el código
git branch -M main
git push -u origin main
```

#### Opción B: Repositorio con Archivos Corruptos (Limpiar)

Si ya tienes archivos en GitHub que quieres reemplazar:

**⚠️ ADVERTENCIA: Esto eliminará todo el historial anterior**

```bash
# Eliminar el remote existente (si existe)
git remote remove origin

# Agregar el remote nuevamente
git remote add origin https://github.com/Adrianmbt/gyma.git

# Forzar el push (esto sobrescribirá el repositorio)
git push -f origin main
```

**Alternativa más segura (crear una rama nueva):**

```bash
# Agregar el remote
git remote add origin https://github.com/Adrianmbt/gyma.git

# Crear y subir en una rama nueva
git checkout -b nueva-version
git push -u origin nueva-version

# Luego en GitHub puedes hacer merge o cambiar la rama principal
```

### 6. Verificar en GitHub

Después de subir, verifica en GitHub que:

- [ ] El README.md se muestra correctamente
- [ ] Los badges funcionan
- [ ] No hay archivos `.env` visibles
- [ ] No hay archivos `.sql` con datos
- [ ] El archivo LICENSE está presente
- [ ] La estructura de carpetas es correcta

## 🔄 Actualizar el Repositorio

Para futuras actualizaciones:

```bash
# Ver estado de cambios
git status

# Agregar cambios
git add .

# Hacer commit
git commit -m "Descripción de los cambios"

# Subir a GitHub
git push origin main
```

## 🌿 Trabajar con Ramas

Para desarrollar nuevas características:

```bash
# Crear una nueva rama
git checkout -b feature/nueva-caracteristica

# Hacer cambios y commits
git add .
git commit -m "Agregar nueva característica"

# Subir la rama
git push origin feature/nueva-caracteristica

# En GitHub, crear un Pull Request para hacer merge
```

## 🔐 Configurar Secretos en GitHub (Opcional)

Si usas GitHub Actions, puedes configurar secretos:

1. Ve a tu repositorio en GitHub
2. Settings → Secrets and variables → Actions
3. Agrega secretos como:
   - `DB_PASSWORD`
   - `APP_KEY`
   - Otras credenciales necesarias

## 📝 Configurar GitHub Pages (Opcional)

Si quieres crear un sitio de documentación:

1. Ve a Settings → Pages
2. Selecciona la rama `main` y carpeta `/docs` (si tienes documentación)
3. Guarda los cambios
4. Tu documentación estará en: `https://adrianmbt.github.io/gyma`

## 🏷️ Crear Releases

Para versionar tu proyecto:

```bash
# Crear un tag
git tag -a v1.0.0 -m "Primera versión estable"

# Subir el tag
git push origin v1.0.0
```

Luego en GitHub:
1. Ve a "Releases"
2. Click en "Create a new release"
3. Selecciona el tag v1.0.0
4. Agrega descripción de cambios
5. Publica el release

## 🛡️ Proteger la Rama Main

Para evitar pushes accidentales:

1. Ve a Settings → Branches
2. Add rule para `main`
3. Activa:
   - Require pull request reviews
   - Require status checks to pass
   - Include administrators

## 📊 Agregar Badges Dinámicos

Puedes agregar más badges al README:

```markdown
![GitHub stars](https://img.shields.io/github/stars/Adrianmbt/gyma?style=social)
![GitHub forks](https://img.shields.io/github/forks/Adrianmbt/gyma?style=social)
![GitHub issues](https://img.shields.io/github/issues/Adrianmbt/gyma)
![GitHub last commit](https://img.shields.io/github/last-commit/Adrianmbt/gyma)
```

## 🔍 Comandos Útiles de Git

```bash
# Ver historial de commits
git log --oneline

# Ver diferencias
git diff

# Deshacer cambios no commiteados
git checkout -- archivo.php

# Deshacer último commit (mantener cambios)
git reset --soft HEAD~1

# Ver ramas
git branch -a

# Cambiar de rama
git checkout nombre-rama

# Eliminar rama local
git branch -d nombre-rama

# Eliminar rama remota
git push origin --delete nombre-rama
```

## ⚠️ Problemas Comunes

### Error: "remote origin already exists"

```bash
git remote remove origin
git remote add origin https://github.com/Adrianmbt/gyma.git
```

### Error: "failed to push some refs"

```bash
# Hacer pull primero
git pull origin main --allow-unrelated-histories

# Luego push
git push origin main
```

### Archivo grande bloqueando el push

```bash
# Ver archivos grandes
git ls-files | xargs ls -lh | sort -k5 -h

# Eliminar del historial (usar con cuidado)
git filter-branch --tree-filter 'rm -f archivo-grande.sql' HEAD
```

## 📞 Soporte

Si tienes problemas:

1. Revisa la [documentación de Git](https://git-scm.com/doc)
2. Revisa la [documentación de GitHub](https://docs.github.com)
3. Busca en [Stack Overflow](https://stackoverflow.com)

---

## ✅ Checklist Final

Antes de considerar el repositorio listo:

- [ ] README.md completo y atractivo
- [ ] LICENSE agregado
- [ ] .gitignore configurado correctamente
- [ ] .env.example con valores de ejemplo
- [ ] Sin archivos sensibles en el repositorio
- [ ] Primer commit realizado
- [ ] Código subido a GitHub
- [ ] README se visualiza correctamente en GitHub
- [ ] Issues habilitados en GitHub
- [ ] Descripción del repositorio agregada
- [ ] Topics/tags agregados (laravel, php, gym, management)

---

**¡Listo! Tu proyecto GYMA está preparado para GitHub** 🎉
