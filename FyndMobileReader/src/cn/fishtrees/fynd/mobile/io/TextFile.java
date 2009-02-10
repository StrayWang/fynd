package cn.fishtrees.fynd.mobile.io;

import java.io.IOException;
import java.io.InputStreamReader;
import java.io.UnsupportedEncodingException;

import cn.fishtrees.fynd.mobile.ui.Console;

public class TextFile extends File {
	private String encoding = "";
	public String getEncoding() {
		return encoding;
	}
	public void setEncoding(String encoding) {
		this.encoding = encoding;
	}
	public TextFile(String fileFullPath){
		super(fileFullPath);
	}
	public InputStreamReader createReader() throws UnsupportedEncodingException, IOException{
		return new InputStreamReader(this.openRead(),this.encoding);
	}
	public long getCharLength(){
		InputStreamReader reader = null;
		long length = 0;
		try
		{
			reader = new InputStreamReader(this.openRead(),this.encoding);
		}
		catch(Exception e){
			
		}
		finally{
			if(null != reader){
				try {
					reader.close();
				} catch (IOException e) {
					Console.WriteLine("TextFile.getCharLength,exception:", e);
				}
			}
		}
		return length;
	}
	
}
