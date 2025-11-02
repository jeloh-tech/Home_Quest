from PIL import Image
import os

def remove_background(input_path, output_path):
    # Open the image
    img = Image.open(input_path)

    # Convert to RGBA if not already
    if img.mode != 'RGBA':
        img = img.convert('RGBA')

    # Get image data
    data = img.getdata()

    # Create new data with white background instead of transparent
    new_data = []
    for item in data:
        # If pixel is white or very light (adjust threshold as needed)
        if item[0] > 240 and item[1] > 240 and item[2] > 240:
            # Make it white with full opacity
            new_data.append((255, 255, 255, 255))
        else:
            # Keep the pixel as is
            new_data.append(item)

    # Update image data
    img.putdata(new_data)

    # Save the image
    img.save(output_path, 'PNG')
    print(f"Background removed and saved to {output_path}")

if __name__ == "__main__":
    input_image = "public/storage/img/akona.png"
    output_image = "public/storage/img/akona_no_bg.png"

    if os.path.exists(input_image):
        remove_background(input_image, output_image)
    else:
        print(f"Input image {input_image} not found")
