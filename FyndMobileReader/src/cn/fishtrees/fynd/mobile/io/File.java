/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package cn.fishtrees.fynd.mobile.io;

import java.io.IOException;
import java.io.InputStream;
import javax.microedition.io.Connector;
import javax.microedition.io.file.FileConnection;

import cn.fishtrees.fynd.mobile.ui.Console;

/**
 * 
 * @author fishtrees
 */
public class File {

	protected String fullPath;
	protected String fileName;
	protected String extensionName;
	protected long length;

	public File(String fullPath) {
		this.fullPath = fullPath;
		this.fileName = FileManager.getFileNameFromPath(this.fullPath);
		this.extensionName = FileManager.getExtensionName(this.fileName);
	}

	public InputStream openRead() throws IOException {
		FileConnection fc = null;
		InputStream fis = null;
		try {
			fc = (FileConnection) Connector.open("file://localhost"
					+ this.fullPath, Connector.READ);
			if (!fc.exists()) {
				Console.WriteLine("File is not exists");
				throw new IOException("File is not exists");
			}
			fis = fc.openInputStream();

		} catch (IOException e) {
			Console.WriteLine(
					"When open file to read,an exception has been throwed", e);
			throw e;
		}
		return fis;
	}

	public long getLength() {
		if (0 == this.length) {
			FileConnection fc = null;
			try {
				fc = (FileConnection) Connector.open("file://localhost"
						+ this.fullPath, Connector.READ);
				this.length = fc.fileSize();
			} catch (IOException e) {
				e.printStackTrace();
			} finally {
				if (null != fc) {
					try {
						fc.close();
					} catch (IOException ex) {
						ex.printStackTrace();
					}
				}
			}
		}
		return this.length;
	}

	public String getFileName() {
		return fileName;
	}
	/**
	 * TODO:getNextCharacter方法不可用
	 * @return
	 * @throws IOException
	 */
	public int getNextCharacter() throws IOException {
		InputStream inpStream = this.openRead();
		int a = inpStream.read();
		int t = a;

		if ((t | 0xC0) == t) {
			int b = inpStream.read();
			if (b == 0xFF) { // Check if legal
				t = -1;
			} else if (b < 0x80) { // Check for UTF8 compliancy
				throw new IOException("Bad UTF-8 Encoding encountered");
			} else if ((t | 0xE0) == t) {
				int c = inpStream.read();
				if (c == 0xFF) { // Check if legal
					t = -1;
				} else if (c < 0x80) { // Check for UTF8 compliancy
					throw new IOException("Bad UTF-8 Encoding encountered");
				} else
					t = ((a & 0x0F) << 12) | ((b & 0x3F) << 6) | (c & 0x3F);
			} else
				t = ((a & 0x1F) << 6) | (b & 0x3F);
		}
		inpStream.close();
		return t;
	}
}
