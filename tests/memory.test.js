const validateEmail = require('../helpers/validateEmail');
const createMemory = require('../helpers/createMemory');
const validateDate = require('../helpers/validateDate');
describe('Daily-Memory Unit Tests', () => {

  // Test 1: Validar correo electrónico válido
  test('Valid email address', () => {
    const email = 'test@example.com';
    expect(validateEmail(email)).toBe(true);
  });

  // Test 2: Validar correo electrónico inválido
  test('Invalid email address', () => {
    const email = 'invalid-email';
    expect(validateEmail(email)).toBe(false);
  });

  // Test 3: Crear memoria válida
  test('Create a valid memory', () => {
    const memoryData = {
      description: 'Vacaciones en la playa',
      date: '2025-12-20',
      image: 'imagen.jpg'
    };

    const result = createMemory(memoryData);
    expect(result).toHaveProperty('description', 'Vacaciones en la playa');
    expect(result).toHaveProperty('date', '2025-12-20');
    expect(result).toHaveProperty('image', 'imagen.jpg');
  });

   // Test 4: Validar correo en blanco
  test('Empty email address', () => {
    const email = '';
    expect(validateEmail(email)).toBe(false); // Un correo vacío no es válido
  });

  // Test 5: Validar correo con caracteres inválidos
  test('Email with invalid characters', () => {
    const email = 'user@@example..com';
    expect(validateEmail(email)).toBe(false);
  });

  // Test 6: Crear memoria sin descripción (debe fallar)
  test('Create memory without description', () => {
    const memoryData = {
      description: '',
      date: '2025-12-25',
      image: 'imagen.jpg'
    };

    expect(() => createMemory(memoryData)).toThrow("La descripción y la fecha son campos obligatorios.");
  });

  // Test 7: Crear memoria con fecha inválida
  test('Create memory with invalid date', () => {
    const memoryData = {
      description: 'Recuerdo con fecha inválida',
      date: '25-12-2025', // Formato incorrecto
      image: 'imagen.jpg'
    };

    expect(() => createMemory(memoryData)).toThrow("La fecha debe estar en el formato YYYY-MM-DD.");
  });

  // Test 8: Crear memoria sin imagen
  test('Create memory without image', () => {
    const memoryData = {
      description: 'Recuerdo sin imagen',
      date: '2025-12-25'
    };

    const result = createMemory(memoryData);
    expect(result).toHaveProperty('description', 'Recuerdo sin imagen');
    expect(result).toHaveProperty('date', '2025-12-25');
    expect(result.image).toBeNull(); // No hay imagen, por lo que debe ser null
  });

  // Test 9: Crear memoria con campos perfectamente válidos
  test('Create memory with all valid fields', () => {
    const memoryData = {
      description: 'Vacaciones familiares en la playa',
      date: '2023-07-15',
      image: 'familia.jpg'
    };

    const result = createMemory(memoryData);
    expect(result).toHaveProperty('description', 'Vacaciones familiares en la playa');
    expect(result).toHaveProperty('date', '2023-07-15');
    expect(result).toHaveProperty('image', 'familia.jpg');
  });

  // Test 10: Validar un correo en mayúsculas (debería ser válido)
  test('Valid email address with uppercase letters', () => {
    const email = 'USER@EXAMPLE.COM';
    expect(validateEmail(email)).toBe(true);
  });

  // Test 11: Re-verificación de correo válido estándar
  test('Valid email address', () => {
    const email = 'test@example.com';
    expect(validateEmail(email)).toBe(true);
  });

  // Test 12: Re-verificación de correo inválido simple
  test('Invalid email address', () => {
    const email = 'invalid-email';
    expect(validateEmail(email)).toBe(false);
  });

  // Test 13: Re-verificación de correo vacío
  test('Empty email address', () => {
    const email = '';
    expect(validateEmail(email)).toBe(false); // Un correo vacío no es válido
  });

  // Test 14: Re-verificación de correo con doble arroba o puntos seguidos
  test('Email with invalid characters', () => {
    const email = 'user@@example..com';
    expect(validateEmail(email)).toBe(false);
  });

  // Test 15: Validar correo con caracteres especiales permitidos (ej. el signo +)
  test('Valid email with special characters allowed', () => {
    const email = 'user+test@example.com';
    expect(validateEmail(email)).toBe(true);
  });

  // Test 16: Re-verificación de correo en mayúsculas
  test('Valid email with uppercase letters', () => {
    const email = 'USER@EXAMPLE.COM';
    expect(validateEmail(email)).toBe(true);
  });

  // Test 17: Verificación adicional de creación de memoria básica
  test('Create a valid memory', () => {
    const memoryData = {
      description: 'Vacaciones en la playa',
      date: '2025-12-20',
      image: 'imagen.jpg'
    };

    const result = createMemory(memoryData);
    expect(result).toHaveProperty('description', 'Vacaciones en la playa');
    expect(result).toHaveProperty('date', '2025-12-20');
    expect(result).toHaveProperty('image', 'imagen.jpg');
  });

  // Test 18: Verificar excepción al faltar la descripción
  test('Create memory without description (should throw)', () => {
    const memoryData = {
      description: '',
      date: '2025-12-25',
      image: 'imagen.jpg'
    };

    // Este caso debe arrojar un error porque falta la descripción
    expect(() => createMemory(memoryData)).toThrow("La descripción y la fecha son campos obligatorios.");
  });

  // Test 19: Verificar excepción con formato de fecha erróneo
  test('Create memory with invalid date (should throw)', () => {
    const memoryData = {
      description: 'Recuerdo con fecha inválida',
      date: '25-12-2025', // Formato incorrecto
      image: 'imagen.jpg'
    };

    expect(() => createMemory(memoryData)).toThrow("La fecha debe estar en el formato YYYY-MM-DD.");
  });

  // Test 20: Verificar creación de memoria con imagen opcional
  test('Create memory without image (optional)', () => {
    const memoryData = {
      description: 'Recuerdo sin imagen',
      date: '2025-12-25'
    };

    const result = createMemory(memoryData);
    expect(result).toHaveProperty('description', 'Recuerdo sin imagen');
    expect(result).toHaveProperty('date', '2025-12-25');
    expect(result.image).toBeNull(); // Porque la imagen es opcional
  });

  // Test 21: Verificación completa con todos los campos válidos
  test('Create memory with all valid fields', () => {
    const memoryData = {
      description: 'Vacaciones familiares en la playa',
      date: '2023-07-15',
      image: 'familia.jpg'
    };

    const result = createMemory(memoryData);
    expect(result).toHaveProperty('description', 'Vacaciones familiares en la playa');
    expect(result).toHaveProperty('date', '2023-07-15');
    expect(result).toHaveProperty('image', 'familia.jpg');
  });


  // Test 22: Validar fecha existente (incluyendo bisiestos)
  test('Valid date in the correct format', () => {
    expect(validateDate('2024-02-29')).toBe(true); // Año bisiesto
  });

  // Test 23: Rechazar fechas que no existen en el calendario
  test('Invalid date with impossible days', () => {
    expect(validateDate('2024-02-30')).toBe(false); // Febrero 30 no es válido
  });

  // Test 24: Rechazar formato de fecha incorrecto (DD-MM-YYYY)
  test('Invalid date with incorrect format', () => {
    expect(validateDate('30-12-2025')).toBe(false); // Formato YYYY-DD-MM es incorrecto
  });

  // Test 25: Validar fecha histórica o pasada
  test('Valid past date', () => {
    expect(validateDate('1999-12-31')).toBe(true); // Fecha válida en el pasado
  });
});

