/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package cn.fishtrees.fynd.mobile.ui;

import gr.fire.core.Component;
import gr.fire.core.Container;
import gr.fire.core.FireScreen;
import gr.fire.core.KeyListener;
import gr.fire.core.LayoutManager;
import gr.fire.core.Panel;
import gr.fire.core.PointerListener;
import gr.fire.ui.TextComponent;

import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.util.Vector;

import javax.microedition.lcdui.Canvas;
import javax.microedition.lcdui.Font;

import cn.fishtrees.fynd.mobile.MemoryManager;
import cn.fishtrees.fynd.mobile.io.File;

/**
 * 文件浏览器，读取UTF8格式的文本文件，以一页32KB分页显示，支持按键导航，触摸屏导航
 * 
 * @author fishtrees
 */
public class FileViewer extends Container implements KeyListener,
		PointerListener {

	/**
	 * 默认分页大小（字节）
	 */
	private static int PAGE_BYTE_SIZE = 32768;// 32KB

	private boolean isPageFirstDisplayed = true;
	/**
	 * 文件完整路径
	 */
	private String fileFullPath;
	/**
	 * 总页数
	 */
	private int pageCount;

	/**
	 * 文件总长度（字节）
	 */
	private long fileLength;
	/**
	 * 当前页码
	 */
	private int currentPageNo;
	/**
	 * 封装的文件操作，目前主要用于读取文件
	 */
	private File file;
	/**
	 * 文件字符编码，目前只支持UTF8格式 TODO:Must support multi-encoding
	 */
	private String encoding = "UTF-8";
	/**
	 * 用于显示文本的控件
	 */
	private TextComponent txtCmp;
	/**
	 * 屏幕指针按下时的X坐标，用于计算拖拽方向
	 */
	private int pointerPressedX;
	/**
	 * 屏幕指针按下时的Y坐标，用于计算拖拽方向
	 */
	private int pointerPressedY;

	/**
	 * 
	 * @param manager
	 *            布局管理器
	 */
	public FileViewer(LayoutManager manager) {
		super(manager);
		this.setKeyListener(this);
		this.setPointerListener(this);
	}

	/**
	 * @return the fileFullPath
	 */
	public String getFileFullPath() {
		return fileFullPath;
	}

	/**
	 * @param fileFullPath
	 *            the fileFullPath to set
	 */
	public void setFileFullPath(String fileFullPath) {
		this.fileFullPath = fileFullPath;
		File f = new File(fileFullPath);
		this.setFile(f);
	}

	/**
	 * 显示上一页
	 */
	public void moveToPrevPage() {
		final int currPageNo = this.currentPageNo;
		new Thread(new Runnable() {
			public void run() {
				displayPage(currPageNo - 1);
			}
		}).start();
	}

	/**
	 * 显示下一页
	 */
	public void moveToNextPage() {
		final int currPageNo = this.currentPageNo;
		new Thread(new Runnable() {
			public void run() {
				displayPage(currPageNo + 1);
			}
		}).start();
	}

	/**
	 * Display "pageNum" parameter specified page
	 * 
	 * @param pageNum
	 */
	public void displayPage(int pageNum) {
		MemoryManager.releasseForce();
		
		if (pageNum <= 0) {
			pageNum = 1;
		}
		if (pageNum > this.pageCount) {
			pageNum = this.pageCount;
		}
		int beginByteOffset = (pageNum == 0) ? PAGE_BYTE_SIZE * (pageNum - 1) 
											 : PAGE_BYTE_SIZE * (pageNum - 1) + 1;

		InputStream ins = null;
		//TODO:将字节读入到vector中，如果按指定的编码解码失败，则向前多读取1个字节
		try {
			byte[] buffer = null;
			int bufferLength = PAGE_BYTE_SIZE;
			if (this.fileLength <= PAGE_BYTE_SIZE) {
				bufferLength = (int) this.fileLength;
				
			} 
			buffer = new byte[bufferLength];
			Console.WriteLine("Buffer was created");
			try {
				ins = this.file.openRead();
				ins.skip(beginByteOffset - 1);
				Console.WriteLine("Skiped " + (beginByteOffset - 1) + " bytes");
				ins.read(buffer);
				Console.WriteLine("Current page has been readed to buffer.");
			} catch (IOException e) {
				this.setTextContent(e.getMessage());
				throw e;
			} finally {
				if (null != ins) {
					try {
						ins.close();
					} catch (IOException ex) {
						Console.WriteLine("When closing input stream,an exception has been throwed",ex);
					}
				}
			}
			String pageText = "";
			try
			{
				pageText = new String(buffer, this.encoding);
			}
			catch(Exception e){
				try
				{
					ins.close();
					buffer = null;
					MemoryManager.releasseForce();
					
					buffer = new byte[bufferLength];
					ins = this.file.openRead();
					ins.skip(beginByteOffset);
					ins.read(buffer);
				}
				catch(IOException ioe){
					Console.WriteLine("Read prev byte error:",ioe);
					throw ioe;
				}
			}
			Console.WriteLine("Buffer has been converted to string with encoding " + this.encoding);
			Console.WriteLine("The pageText's length is " + pageText.length());
			buffer = null;
			
			this.setPanelTitle();
			this.setTextContent(pageText);

			Console.WriteLine("FileViewer.displayPage : current page No. is '" + pageNum + "'");
			
			this.currentPageNo = pageNum;

		} catch (Exception ex) {
			Console.WriteLine("When displaying page,an exception has been throwed," + ex.getClass(), ex);
		}
	}

	public void keyPressed(int code, Component src) {
	}

	public void keyReleased(int code, Component src) {
		int gameAction = FireScreen.getScreen().getGameAction(code);
		switch (gameAction) {
		case Canvas.DOWN:
			this.scrollDown(false);
			break;
		case Canvas.LEFT:
			this.moveToNextPage();
			break;
		case Canvas.RIGHT:
			this.moveToPrevPage();
			break;
		case Canvas.UP:
			this.scrollUp(false);
			break;
		}
	}

	public void keyRepeated(int code, Component src) {
		// throw new UnsupportedOperationException("Not supported yet.");
	}

	public void pointerDragged(int x, int y, Component src) {

	}

	public void pointerPressed(int x, int y, Component src) {
		this.pointerPressedX = x;
		this.pointerPressedY = y;
	}

	public void pointerReleased(int x, int y, Component src) {
		int increaseX = x - this.pointerPressedX;
		int increaseY = y - this.pointerPressedY;
		int absIncreaseX = Math.abs(increaseX);
		int absIncreaseY = Math.abs(increaseY);

		if (absIncreaseX > absIncreaseY) {
			// pointer moved in horizontal direction
			if (increaseX > 0) {
				// pointer moved from left to right,we will display the previous
				// page.
				this.moveToPrevPage();
			} else if (increaseX < 0) {
				// pointer moved from right to left,we will show the next page.
				this.moveToNextPage();
			}
		} else if (absIncreaseX < absIncreaseY) {
			// pointer moved in vertical direction
			if (increaseY > 0) {
				this.scrollUp(false);
			} else if (increaseY < 0) {
				this.scrollDown(false);
			}
		}
	}

	/**
	 * 代理方法，调用父组件scrollUp向上移动滚动条
	 * 
	 * @param fast
	 */
	protected void scrollUp(boolean fast) {
		if (this.parent != null && this.parent instanceof Panel) {
			((Panel) this.parent).scrollUp(fast);
		}

	}

	/**
	 * 代理方法，调用父组件scrollDown向下移动滚动条
	 * 
	 * @param fast
	 */
	protected void scrollDown(boolean fast) {
		if (this.parent != null && this.parent instanceof Panel) {
			((Panel) this.parent).scrollDown(fast);
		}

	}

	/**
	 * @return the file
	 */
	public File getFile() {
		return file;
	}

	/**
	 * @param file
	 *            the file to set
	 */
	public void setFile(File file) {
		if (null != file) {
			this.file = file;
			this.fileLength = file.getLength();
			Console.WriteLine("File's length is " + this.fileLength);
			this.pageCount = (int) Math.ceil((double) this.fileLength
					/ (double) PAGE_BYTE_SIZE);
			this.currentPageNo = 1;
			this.displayPage(this.currentPageNo);
		}
	}

	/**
	 * @return the text file's encoding
	 */
	public String getEncoding() {
		return encoding;
	}

	/**
	 * @param encoding
	 *            the encoding to set
	 */
	public void setEncoding(String encoding) {
		this.encoding = encoding;
	}

	/**
	 * 将读取的文本显示到文本控件上
	 * 
	 * @param text
	 */
	protected void setTextContent(String text) {
		TextComponent newTextCmp = new TextComponent(text);
		newTextCmp.setFont(Font.getFont(Font.FACE_SYSTEM, Font.STYLE_PLAIN,
				Font.SIZE_MEDIUM));
		newTextCmp.validate();
		int textHeight = newTextCmp.getHeight();
		if (textHeight > this.height) {
			this.height = textHeight;
			this.setPrefSize(this.width, textHeight);
		}
		if (null != this.txtCmp) {
			this.remove(this.txtCmp);
			this.txtCmp = null;
		}

		this.txtCmp = newTextCmp;
		this.add(this.txtCmp);
		this.valid = false;
		this.validate();
		if (!this.isPageFirstDisplayed) {
			if (this.parent != null) {
				this.parent.repaint();
			} else {
				this.repaint();
			}
		}
		this.isPageFirstDisplayed = false;
	}
	public void cleanCurrentPage(){
		if(this.txtCmp != null){
			this.remove(this.txtCmp);
		}
	}

	public int getCurrentPageNo() {
		return currentPageNo;
	}

	public int getPageCount() {
		return pageCount;
	}

	protected void setPanelTitle() {
		if (this.parent != null && this.parent instanceof Panel) {
			Panel p = (Panel) this.parent;
			p.setLabel(this.file.getFileName() + " " + this.currentPageNo + "/"
					+ this.pageCount);
			Console.WriteLine("Panle's title has been setted!");
		}
	}

	public void setParent(Panel p) {
		this.parent = p;
	}
}
