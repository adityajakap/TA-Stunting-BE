
<div>
    <label for="name">Nama Menu</label>
    <input type="text" id="name" name="name" value="{{ old('name', $menu->name ?? '') }}" required>
</div>

<div>
    <label for="nutrition">Nutrisi</label>
    <textarea id="nutrition" name="nutrition" rows="4" required>{{ old('nutrition', $menu->nutrition ?? '') }}</textarea>
</div>

<div>
    <label for="ingredients">Bahan-bahan</label>
    <textarea id="ingredients" name="ingredients" rows="4" required>{{ old('ingredients', $menu->ingredients ?? '') }}</textarea>
</div>

<div>
    <label for="instructions">Cara Membuat</label>
    <textarea id="instructions" name="instructions" rows="4" required>{{ old('instructions', $menu->instructions ?? '') }}</textarea>
</div>

<div>
    <label for="category">Kategori</label>
    <select id="category" name="category" required>
        @foreach(['pagi', 'siang', 'malam', 'snack'] as $kategori)
            <option value="{{ $kategori }}" {{ (old('category', $menu->category ?? '') == $kategori) ? 'selected' : '' }}>{{ ucfirst($kategori) }}</option>
        @endforeach
    </select>
</div>

<div>
    <label for="image">Upload Gambar</label>
    <input type="file" id="image" name="image">
    @if (!empty($menu->image))
        <p>Gambar Saat Ini:</p>
        <img src="{{ asset('storage/' . $menu->image) }}" width="200">
    @endif
</div>
