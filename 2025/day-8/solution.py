import math
import copy
import pprint

filename = 'input-test.txt'

class Junction_Box:
    def __init__(self, x, y, z):
        self.x = x
        self.y = y
        self.z = z

    def calculate_distance(self, point2: 'Junction_Box') -> float:
        return math.sqrt(
          (self.x - point2.x) ** 2 + (self.y - point2.y) ** 2 + (self.z - point2.z) ** 2
        )

    def __repr__(self):
        return "(" + str(self.x) + "," + str(self.y) + "," + str(self.z) + ")"

boxes = {}

def find_nearest_boxes():
    shortest_distance = 1000000000000
    shortest_box1 = None
    shortest_box2 = None
    for box_str, box_value in boxes.items():
        if (box_value != None):
            continue
        for box2_str, box_value in boxes.items():
            if box_str != box2_str:
                coords = box_str.split(',')
                box = Junction_Box(
                  int(coords[0]),
                  int(coords[1]),
                  int(coords[2])
                )
                coords = box2_str.split(',')
                box2 = Junction_Box(
                  int(coords[0]),
                  int(coords[1]),
                  int(coords[2])
                )


                distance = box.calculate_distance(box2)

                if (shortest_distance > distance):
                    shortest_distance = distance
                    shortest_box1 = box_str
                    shortest_box2 = box2_str

    print(f"Next nearest {shortest_box1} => {shortest_box2}: {shortest_distance}")
    return shortest_box1, shortest_box2

# init box
with open(filename) as file:
    for line in file:
        line = line.rstrip()
        boxes[line] = None

count = 0
connection_number=0
while count < 10:
    shortest_box1, shortest_box2 = find_nearest_boxes()
    count = count+1
    if (shortest_box2 == None or shortest_box1 == None):
        break

    if (boxes[shortest_box2] == None and boxes[shortest_box1] == None):
        connection_number += 1
        boxes[shortest_box1] = connection_number
        boxes[shortest_box2] = connection_number
    else:
        boxes[shortest_box1] = boxes[shortest_box2] or boxes[shortest_box1]
        boxes[shortest_box2] = boxes[shortest_box1] or boxes[shortest_box2]

    pprint.pprint(boxes)

box_sizes = {}
for box_str, box_value in boxes.items():
    box_sizes[box_value] = box_sizes[box_value] + 1 if box_value in box_sizes else 1

pprint.pprint(box_sizes)

sorted_box_sizes = sorted(
  [(k, v) for k, v in box_sizes.items() if k is not None],
  key=lambda x:x[1],
  reverse=True
)
pprint.pprint(sorted_box_sizes)

result1 = sorted_box_sizes[0][1] * sorted_box_sizes[1][1] * sorted_box_sizes[2][1]

print(f"Part 1 result: {result1}")


