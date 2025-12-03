filename = 'input.txt'

def get_joltage(array, nb_batteries_needed):
    if nb_batteries_needed == 0:
        return ''

    if nb_batteries_needed == 1:
        first_max = max(array)
    else:
        first_max = max(array[:-(nb_batteries_needed-1)])

    index_first_max = array.index(first_max)
    sub_array = array[index_first_max+1:]

    if (len(sub_array) == 0):
        sub_joltage = ''
    else:
        sub_joltage = get_joltage(sub_array, nb_batteries_needed - 1)

    joltage = int(str(first_max) + str(sub_joltage))

    return joltage


with open(filename) as file:
    result1 = 0
    result2 = 0
    for line in file:
        array = list(map(int, list(line.rstrip())))
        result1 += get_joltage(array, 2)
        result2 += get_joltage(array, 12)

    print(f"Part 1 result: {result1}")
    print(f"Part 2 result: {result2}")

