export default(response) => {
    const data = response.data;
    const currentData = data.data;
    const items = currentData.items;

    return items;
};
