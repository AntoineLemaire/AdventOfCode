import copy
filename = 'input.txt'

ranges=[]
ids=[]
result1=0
result2=0

def append_to_existing_ranges(to_insert_range):

    if (len(ranges) == 0):
        ranges.append(to_insert_range)
    else:
        found=False
        new_range=None
        index_to_remove=None

        for i, current_range in enumerate(ranges):

            if (current_range[0] >= to_insert_range[0] and current_range[0] < to_insert_range[1]
               and current_range[1] <= to_insert_range[1] and current_range[1] > to_insert_range[0]):
               # total replace of range
               found=True
               index_to_remove = i
               new_range=copy.deepcopy(to_insert_range)
               break

            elif (to_insert_range[0] >= current_range[0] and (to_insert_range[0]-1) <= current_range[1]):
                found=True
                if to_insert_range[1] > current_range[1]:
                    # end current_range can be replaced
                    new_range = copy.deepcopy(ranges[i])
                    new_range[1] = to_insert_range[1]
                    index_to_remove = i
                    break


            elif (to_insert_range[1]+1) >= current_range[0] and to_insert_range[1] <= current_range[1]:
                found=True
                if to_insert_range[0] < current_range[0]:
                    # start current_range can be replaced
                    new_range = copy.deepcopy(ranges[i])
                    new_range[0] = to_insert_range[0]
                    index_to_remove = i
                    break

        if found==False:
            # No correspondent found, insert {to_insert_range} directly
            ranges.append(to_insert_range)
        elif new_range != None:
            ranges.pop(index_to_remove)
            append_to_existing_ranges(new_range)

with open(filename) as file:
    for line in file:
        line = line.rstrip()

        if line.find('-') != -1:
            to_insert_range = list(map(int, line.split('-')))
            append_to_existing_ranges(to_insert_range)

        elif line == "":
          continue
        else:
            ids.append(int(line))

for id in ids:
    for current_range in ranges:
        if id >= current_range[0] and id <= current_range[1]:
            result1 += 1
            break

for current_range in ranges:
    result2 += (current_range[1] - current_range[0] + 1)

print(f"Part 1 result: {result1}")
print(f"Part 1 result: {result2}")
