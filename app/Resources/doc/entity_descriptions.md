Schooly
===========

## Descripción de entidades:

### Usuarios:

#### Alumno:
- Estado de un usuario suscrito a una Convocaria de un Grado
    
#### Profesor:
- Estado de un usuario cuando administra un grado

### Grado:
- Contiene Convocatorias mediante las que los Alumnos pueden obtener Cursos

### Convocatoria:
- El Alumno dispone acceso al contenido del Curso

### Curso:
- Forma parte de una Convocatoria
- Se divide en Secciónes
- El curso lo imparten uno o varios Profesores
    
### Seccion:
- Puede depender de fecha de inicio y fin
- Contiene los contenidos del Curso
    
### Lección:
- Lecciones en video
- Lecciones en podcast
- Lecciones en slides
- Lecciones en texto
- Puede ser de uno o varios tipos
    
### Cuestionario:
- Colección de Preguntas
- Se pueden poner preguntas del banco de preguntas de la Lección

### Colección de preguntas:
- todas las preguntas relacionadas con una sección
    
### Examen:
- Examen práctico
- Examen test
- Examen texto
- Examen mixto
- Puede se requisito para aprobar una sección

### Pregunta:
- Pregunta test
- Pregunta texto


## Data structure:

* **User:**

* **Grade:**
    * subject
    * picture
    * description
    * __GradeSessions__
    
* **GradeSession:**
    * start_date
    * end_date
    * __Courses__
    * __Teachers__
    * __Students__

* **Course:**
    * name | subject
    * description
    * enabled
    * image
    * __Sections__

* **Section:**
    * subject
    * duration
    * __Lessons__
    * __Tests__
    * __Assignments__
    * __Practices__
    
* **Lesson:**
    * subject
    * description
    * enabled
    * __Resources__
    
* **Assignment:**
    * subject
    * description
    * __Tasks__
    * __Questions__
    * __Resources__
    
* **QuestionBank:**
    * __Questions__
    
* **Test:**
    * repeatable
    * __Questions__
    
* **Question:**
    * statement
    * picture
    * __PosibleAnswers__

* **Practice:**
    * name
    * description
    * submit_help

* **Resource:**
    * name
    * description
    * file





