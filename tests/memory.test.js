const validateEmail = require('../helpers/validateEmail');
const createMemory = require('../helpers/createMemory');

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
});