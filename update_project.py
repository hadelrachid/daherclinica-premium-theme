import os
import shutil

# --- CONFIGURAÇÃO AUTOMÁTICA ---
# O script detecta a pasta onde ele está localizado
project_path = os.path.dirname(os.path.abspath(__file__))
project_name = os.path.basename(project_path)
output_file = os.path.join(project_path, 'project.txt')

# Extensões de arquivos permitidos para leitura de conteúdo
allowed_extensions = ('.php', '.css', '.js', '.txt', '.json', '.md', '.html', '.py', '.xml', '.sql')
# Pastas que devem ser ignoradas na busca
ignore_dirs = ['.git', '.vscode', '__pycache__', 'node_modules', 'vendor', 'tests', 'temp', 'auditoria']
# Arquivos que devem ser ignorados
ignore_files = ['project.txt', 'update_project.py', '.env', 'database.sqlite']

def is_test_file(filename):
    name = filename.lower()
    return name.startswith('test-') or name.startswith('test_') or name == 'test.php'

def get_tree(startpath):
    tree = []
    
    def build_tree(path, prefix=""):
        # Ignorar arquivos de sistema e o próprio script/saída
        ignore = ['project.txt', 'update_project.py', 'screenshot.png', '.git', '.vscode', '__pycache__', '.env', 'database.sqlite', 'temp', 'auditoria']
        items = [i for i in os.listdir(path) if i not in ignore and not i.endswith('.tmp') and not is_test_file(i)]
        
        # Ordenar: Pastas primeiro, depois arquivos (alfabético)
        items.sort(key=lambda x: (not os.path.isdir(os.path.join(path, x)), x.lower()))
        
        count = len(items)
        for i, item in enumerate(items):
            is_last = (i == count - 1)
            connector = "└── " if is_last else "├── "
            
            full_path = os.path.join(path, item)
            if os.path.isdir(full_path):
                tree.append(f"{prefix}{connector}📁 {item}/")
                new_prefix = prefix + ("    " if is_last else "│   ")
                build_tree(full_path, new_prefix)
            else:
                ext = os.path.splitext(item)[1].lower()
                icon = "📄"
                if ext == '.php': icon = "🐘"
                elif ext == '.css': icon = "🎨"
                elif ext == '.js': icon = "📜"
                elif ext in ['.png', '.jpg', '.jpeg', '.webp', '.ico']: icon = "🖼️"
                elif ext == '.txt': icon = "📝"
                elif ext == '.json': icon = "⚙️"
                elif ext == '.md': icon = "📖"
                
                tree.append(f"{prefix}{connector}{icon} {item}")

    tree.append(f"📦 {project_name}/")
    build_tree(startpath)
    return "\n".join(tree) + "\n"

def update_project():
    print("Gerando árvore do projeto...")
    tree_structure = get_tree(project_path)
    
    print("Iniciando a produção do arquivo project.txt...")
    file_count = 0
    
    with open(output_file, 'w', encoding='utf-8') as f_out:
        f_out.write(tree_structure + "\n")
        
        # 1. Incluir todos os arquivos do projeto (baseado nas extensões permitidas)
        for root, dirs, files in os.walk(project_path):
            # Filtrar diretórios para não entrar neles
            dirs[:] = [d for d in dirs if d not in ignore_dirs]
            
            for f_name in files:
                # Ignorar arquivos indesejados
                if f_name in ignore_files or f_name.endswith(('.min.css', '.min.js', '.zip')) or is_test_file(f_name):
                    continue
                
                # Apenas incluir arquivos com as extensões permitidas
                if f_name.endswith(allowed_extensions):
                    f_path = os.path.join(root, f_name)
                    relative_path = os.path.relpath(f_path, project_path)
                    
                    file_count += 1
                    # Status de produção no terminal
                    print(f"Lendo ({file_count}): {relative_path[:60]:<60}", end='\r')
                    
                    f_out.write("/* " + "="*60 + " */\n")
                    f_out.write(f"// Arquivo: {project_name}/{relative_path}\n\n")
                    try:
                        with open(f_path, 'r', encoding='utf-8', errors='ignore') as f_in:
                            f_out.write(f_in.read() + "\n\n")
                    except Exception as e:
                        f_out.write(f"Erro ao ler arquivo: {e}\n\n")

    print(f"\n[OK] project.txt produzido com sucesso! ({file_count} arquivos incluídos)")
    
    # 3. Compactar a pasta inteira em um arquivo ZIP
    import tempfile
    
    full_zip_path = os.path.join(project_path, f"{project_name}.zip")
    
    # Passo A: Prevenção de Inception (Deletar o zip antigo se existir)
    if os.path.exists(full_zip_path):
        os.remove(full_zip_path)
        print(f"Versão anterior {project_name}.zip deletada.")
        
    print(f"Iniciando compactação segura para {project_name}.zip...")
    
    # Passo B: Criar o zip em uma pasta temporária filtrando arquivos indesejados
    import zipfile
    temp_dir = tempfile.gettempdir()
    temp_zip_base = os.path.join(temp_dir, f"{project_name}.zip")
    
    ignore_for_zip = ['.git', '.gitignore', '.vscode', '__pycache__', 'update_project.py', 'project.txt', f"{project_name}.zip", '.env', 'database.sqlite', '.phpunit.result.cache', 'tests', 'temp', 'auditoria']
    
    # Pré-calcula a lista de arquivos para a barra de progresso
    files_to_zip = []
    for root, dirs, files in os.walk(project_path):
        # Filtra diretórios para não entrar neles
        dirs[:] = [d for d in dirs if d not in ignore_for_zip]
        
        for file in files:
            if file in ignore_for_zip or file.endswith('.zip') or is_test_file(file):
                continue
            file_path = os.path.join(root, file)
            arcname = os.path.relpath(file_path, project_path)
            files_to_zip.append((file_path, arcname))
            
    total_zip = len(files_to_zip)
    zip_count = 0
    
    with zipfile.ZipFile(temp_zip_base, 'w', zipfile.ZIP_DEFLATED) as zipf:
        for file_path, arcname in files_to_zip:
            zip_count += 1
            percent = (zip_count / total_zip) * 100
            
            # Barra estilo Linux
            bar_length = 20
            filled_len = int(bar_length * zip_count // total_zip)
            bar = '=' * filled_len + '>' + ' ' * (bar_length - filled_len - 1)
            if zip_count == total_zip:
                bar = '=' * bar_length
            
            print(f"[{bar}] {percent:5.1f}% | {arcname[:45]:<45}", end='\r')
            zipf.write(file_path, arcname)
    
    # Passo C: Mover o zip finalizado de volta para a pasta do projeto
    shutil.move(temp_zip_base, full_zip_path)
    
    print(f"\n[OK] Projeto compactado com sucesso em: {full_zip_path} ({zip_count} arquivos no pacote)")

if __name__ == "__main__":
    update_project()
