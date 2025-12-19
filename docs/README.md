# Documentación LaraShop

Bienvenido a la documentación del proyecto LaraShop. Este directorio contiene toda la planificación, arquitectura y guías necesarias para el desarrollo de la plataforma e-commerce.

---

## DOCUMENTOS DISPONIBLES

### 1. PLAN_MAESTRO.md
**Documento principal del proyecto**

Contiene:
- Visión general del proyecto
- Arquitectura del sistema completa
- Esquema de base de datos resumido
- Estructura de directorios detallada
- Plan de implementación por fases (20 fases)
- Descripción de servicios y repositorios
- Guía de frontend con Blade y Tailwind
- Sistema de módulos y extensibilidad
- Estrategia de testing
- Checklist de seguridad

Empieza por este documento para entender el alcance completo del proyecto.

### 2. DATABASE_SCHEMA.md
**Esquema detallado de base de datos**

Contiene:
- Convenciones de nomenclatura
- Diagramas de relaciones por módulo
- Definición completa de las 34 tablas
- Índices y optimizaciones
- Triggers y procedimientos almacenados
- Ejemplos de migraciones en Laravel
- Resumen de conteo de tablas

Consulta este documento cuando necesites entender la estructura de datos o crear migraciones.

### 3. GUIA_INICIO.md
**Guía de inicio rápido**

Contiene:
- Prerrequisitos del proyecto
- Configuración inicial paso a paso
- Instalación de Laravel Breeze
- Configuración de Tailwind CSS
- Creación de estructura de directorios
- Primeras migraciones y modelos
- Seeders básicos
- Factories para testing
- Comandos útiles

Usa este documento para comenzar el desarrollo desde cero.

### 4. EJEMPLOS_CODIGO.md
**Ejemplos de implementación**

Contiene:
- Servicios completos (PriceCalculator, CartService, OrderService)
- Acciones (Actions pattern)
- DTOs (Data Transfer Objects)
- Eventos y Listeners
- Middleware personalizados
- Form Requests
- Componentes Blade
- Controllers
- Rutas

Consulta este documento cuando necesites ejemplos de cómo implementar cada componente del sistema.

---

## ORDEN DE LECTURA RECOMENDADO

### Para Desarrolladores Nuevos en el Proyecto:

1. **PLAN_MAESTRO.md** - Secciones: Visión General, Arquitectura del Sistema
   - Entiende qué vamos a construir y cómo

2. **DATABASE_SCHEMA.md** - Sección: Diagrama de Relaciones
   - Visualiza cómo se relacionan los datos

3. **GUIA_INICIO.md** - Completo
   - Configura tu entorno y crea los primeros componentes

4. **EJEMPLOS_CODIGO.md** - Según necesites
   - Consulta ejemplos mientras desarrollas

### Para Planificación de Sprints:

1. **PLAN_MAESTRO.md** - Sección: Plan de Implementación por Fases
   - Identifica las tareas de cada fase

2. **DATABASE_SCHEMA.md** - Según la fase
   - Revisa las tablas necesarias para cada sprint

### Para Consultas Rápidas:

- Necesitas saber qué campos tiene una tabla? → **DATABASE_SCHEMA.md**
- Necesitas ver cómo implementar un servicio? → **EJEMPLOS_CODIGO.md**
- Necesitas saber en qué fase estamos? → **PLAN_MAESTRO.md**
- Necesitas crear una nueva migración? → **GUIA_INICIO.md** + **DATABASE_SCHEMA.md**

---

## ESTRUCTURA DEL PROYECTO POR FASES

### Fase 1-2: FUNDAMENTOS Y CATÁLOGO (Semanas 1-4)
- Setup inicial
- Autenticación
- Productos, categorías, marcas
- Atributos y combinaciones

**Documentos relevantes:**
- GUIA_INICIO.md (completo)
- DATABASE_SCHEMA.md (tablas 1-4)
- EJEMPLOS_CODIGO.md (ProductService, ProductRepository)

### Fase 3-4: CARRITO Y PRECIOS (Semanas 5-6)
- Carrito de compras
- Sistema de precios
- Impuestos y descuentos

**Documentos relevantes:**
- DATABASE_SCHEMA.md (tablas 5-6)
- EJEMPLOS_CODIGO.md (CartService, PriceCalculator)

### Fase 5-6: CHECKOUT Y ENVÍOS (Semanas 7-9)
- Flujo de checkout
- Pedidos
- Sistema de envíos

**Documentos relevantes:**
- DATABASE_SCHEMA.md (tablas 7-8)
- EJEMPLOS_CODIGO.md (OrderService, CheckoutController)

### Fase 7-8: PAGOS Y EMAILS (Semanas 10-11)
- Integración de pagos
- Sistema de notificaciones

**Documentos relevantes:**
- PLAN_MAESTRO.md (Fase 7-8)
- EJEMPLOS_CODIGO.md (Events, Listeners, Mailables)

### Fase 9-13: CARACTERÍSTICAS AVANZADAS (Semanas 12-16)
- CMS
- Multilenguaje
- SEO
- Stock avanzado
- Configuración global

**Documentos relevantes:**
- DATABASE_SCHEMA.md (tablas 9-11)
- PLAN_MAESTRO.md (Fases correspondientes)

### Fase 14-16: TESTING Y PRODUCCIÓN (Semanas 17-20)
- Tests completos
- Módulos
- Deploy

**Documentos relevantes:**
- PLAN_MAESTRO.md (Testing, Seguridad)

---

## CONVENCIONES Y ESTÁNDARES

### Código
- PSR-12 para PHP
- Camel case para métodos: `getUserCart()`
- Snake case para base de datos: `product_images`
- Nombres descriptivos en español para UI
- Comentarios en español

### Commits
```
feat: añadir sistema de carrito de compras
fix: corregir cálculo de impuestos en checkout
refactor: optimizar consultas de productos
docs: actualizar diagrama de base de datos
test: añadir tests para OrderService
```

### Ramas
- `main` - Producción
- `develop` - Desarrollo
- `feature/nombre-funcionalidad` - Nuevas características
- `fix/nombre-bug` - Correcciones

---

## RECURSOS ADICIONALES

### Enlaces Útiles
- Laravel Documentation: https://laravel.com/docs
- Tailwind CSS: https://tailwindcss.com/docs
- Pest PHP: https://pestphp.com/docs
- Laravel Breeze: https://laravel.com/docs/starter-kits

### Dependencias Clave
- PHP 8.2+
- Laravel 12.x
- MySQL 8.0+
- Node.js 18+

---

## GLOSARIO

- **DTO**: Data Transfer Object - Objeto para transferir datos entre capas
- **Service**: Clase que contiene lógica de negocio
- **Repository**: Patrón para abstraer acceso a datos
- **Action**: Clase de una sola responsabilidad para una acción específica
- **Scope**: Query scope de Eloquent para filtros reutilizables
- **Accessor**: Atributo virtual calculado en modelos
- **Seeder**: Clase para poblar la base de datos con datos iniciales
- **Factory**: Clase para generar datos de prueba

---

## CONTACTO Y SOPORTE

Para dudas sobre:
- **Arquitectura**: Consulta PLAN_MAESTRO.md
- **Base de datos**: Consulta DATABASE_SCHEMA.md
- **Implementación**: Consulta EJEMPLOS_CODIGO.md
- **Setup inicial**: Consulta GUIA_INICIO.md

---

## ACTUALIZACIONES

Este directorio se actualizará conforme avance el proyecto. Revisa regularmente los documentos para mantenerte al día con los cambios.

**Última actualización:** 19 de diciembre de 2025
**Versión de documentación:** 1.0
