function createMemory(memoryData) {
    const { description, date, image } = memoryData;

    if (!description || !date) {
        throw new Error("La descripción y la fecha son campos obligatorios.");
    }

    const dateRegex = /^\d{4}-\d{2}-\d{2}$/;
    if (!dateRegex.test(date)) {
        throw new Error("La fecha debe estar en el formato YYYY-MM-DD.");
    }

    return {
        description,
        date,
        image: image || null
    };
}

module.exports = createMemory;
