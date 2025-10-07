# ⚡ Inicio Rápido - Subir GYMA a GitHub

## 🎯 Objetivo

Subir tu proyecto GYMA a GitHub de forma rápida y segura.

## ✅ Pre-requisitos

- [x] Git instalado ([Descargar](https://git-scm.com/))
- [x] Cuenta de GitHub
- [x] Repositorio creado: https://github.com/Adrianmbt/gyma

## 🚀 Opción 1: Automático (Recomendado)

### Paso Único

```bash
# Ejecutar el script
subir_a_github.bat
```

**¡Eso es todo!** El script hace todo por ti.

---

## 🔧 Opción 2: Manual (5 comandos)

### Comandos

```bash
# 1. Inicializar
git init

# 2. Agregar archivos
git add .

# 3. Commit
git commit -m "Initial commit: GYMA - Sistema de Gestión de Gimnasio"

# 4. Conectar con GitHub
git remote add origin https://github.com/Adrianmbt/gyma.git
git branch -M main

# 5. Subir
git push -u origin main
```

---

## 🔄 Si el Repositorio Tiene Archivos Corruptos

### Opción A: Sobrescribir Todo

```bash
git init
git add .
git commit -m "Initial commit: GYMA - Sistema de Gestión de Gimnasio"
git remote add origin https://github.com/Adrianmbt/gyma.git
git branch -M main
git push -f origin main
```

### Opción B: Rama Nueva

```bash
git init
git add .
git commit -m "Initial commit: GYMA - Sistema de Gestión de Gimnasio"
git remote add origin https://github.com/Adrianmbt/gyma.git
git checkout -b nueva-version
git push -u origin nueva-version
```

Luego en GitHub, cambia la rama principal a `nueva-version`.

---

## ✅ Verificar en GitHub

Después de subir, ve a: https://github.com/Adrianmbt/gyma

Verifica:
- [ ] README se ve bien
- [ ] No hay archivos .env
- [ ] No hay archivos .sql

---

## 🎨 Configurar el Repositorio

1. **Agregar descripción**:
   ```
   Sistema completo de gestión para gimnasios con Laravel 9
   ```

2. **Agregar topics**:
   - `laravel`
   - `php`
   - `mysql`
   - `gym-management`
   - `bootstrap`

3. **Habilitar Issues**: Settings → Features → Issues ✅

---

## 📚 Documentación Completa

Para más detalles, consulta:

- `README.md` - Documentación del proyecto
- `PREPARAR_GITHUB.md` - Guía detallada
- `CHECKLIST_SEGURIDAD.md` - Verificación de seguridad
- `RESUMEN_PREPARACION.md` - Resumen completo

---

## 🐛 Problemas Comunes

### "Git no reconocido"
**Solución**: Instala Git desde https://git-scm.com/

### "Permission denied"
**Solución**: Verifica tus credenciales de GitHub

### "Remote origin already exists"
**Solución**:
```bash
git remote remove origin
git remote add origin https://github.com/Adrianmbt/gyma.git
```

### "Failed to push"
**Solución**:
```bash
git pull origin main --allow-unrelated-histories
git push origin main
```

---

## 💡 Tip

Si tienes dudas, ejecuta el script `subir_a_github.bat` que hace todo automáticamente.

---

## 📞 Ayuda

Si necesitas ayuda:
1. Lee `PREPARAR_GITHUB.md`
2. Revisa `CHECKLIST_SEGURIDAD.md`
3. Consulta la documentación de Git

---

**¡Listo! Tu proyecto estará en GitHub en menos de 5 minutos.** 🎉
