import copy

filename = 'input.txt'

def get_map():
    map = []
    with open(filename) as file:
      for line in file:
          # Transform each line into a list of characters
          map.append(list(line.rstrip()))
    return map

def can_lift(map, x, y):
    count = 0
    for dy in [-1, 0, 1]:
        for dx in [-1, 0, 1]:
            if (dx == 0 and dy == 0):
                continue
            nx = x + dx
            ny = y + dy

            # Check bounds
            if (nx < 0 or ny < 0 or ny >= len(map) or nx >= len(map[0])):
                continue

            if (map[ny][nx] == '@'):
                count += 1

            if count >= 4:
                return False

    return True

def iterate_and_count(map, recursive=False):
    width = len(map[0])
    height = len(map)
    # Deep copy, to avoid modifying the original map while iterating
    new_map = copy.deepcopy(map)

    result = 0
    for y in range(height):
        for x in range(width):
            if (map[y][x] == '@' and can_lift(map, x, y)):
                result += 1
                new_map[y][x] = '.'

    if result == 0:
        return 0
    elif (recursive):
        return result + iterate_and_count(new_map, True)
    else:
        return result

map = get_map()

result1 = iterate_and_count(map)
result2 = iterate_and_count(map, True)


print(f"Part 1 result: {result1}")
print(f"Part 2 result: {result2}")
