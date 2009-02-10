/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package cn.fishtrees.fynd.mobile.ui;

import gr.fire.core.CommandListener;
import gr.fire.core.Component;
import gr.fire.core.FireScreen;
import gr.fire.core.LayoutManager;
import gr.fire.core.Panel;

import java.io.IOException;
import java.util.Enumeration;

import javax.microedition.lcdui.Command;
import javax.microedition.lcdui.Displayable;
import javax.microedition.lcdui.Image;

import cn.fishtrees.fynd.mobile.MobileReaderCommands;
import cn.fishtrees.fynd.mobile.io.FileManager;

/**
 * 
 * @author fishtrees
 */
public class FileList extends List implements CommandListener {

	private FileManager fileManager;
	private Image dirIcon;
	private Image fileIcon;
	private FileViewer viewer;

	public FileList(LayoutManager lm) {
		super(lm);

		this.fileManager = new FileManager();
		this.viewer = new FileViewer(lm);
		try {
			this.dirIcon = Image.createImage("/res/icons/dir.png");
		} catch (IOException ex) {
			ex.printStackTrace();
		}
		try {
			this.fileIcon = Image.createImage("/res/icons/file.png");
		} catch (IOException ex) {
			ex.printStackTrace();
		}
	}

	public void initList(String dir) {
		this.fileManager.changeDir(dir);
		this.initList();
	}

	public void initList() {
		try {
			this.removeAll();
			Console.WriteLine(this.fileManager.getCurrentDir());
			ListItem item = null;
			int itemHeightCount = 0;
			if (!this.fileManager.getCurrentDir().equals(FileManager.MEGA_ROOT)) {
				item = new ListItem(this.dirIcon, "..");
				item.setValue(FileManager.UP_DIRECTORY);
				item.setCommand(MobileReaderCommands.CMD_DIR);
				item.setCommandListener(this);
				this.add(item);
				itemHeightCount += item.getHeight();
				Console.WriteLine("add root item to list");
			}
			Enumeration en = this.fileManager.listDirectory();
			
			while (en.hasMoreElements()) {
				String fileName = (String) en.nextElement();

				if (fileName.charAt(fileName.length() - 1) == FileManager.SEP) {
					// This is directory
					item = new ListItem(this.dirIcon, fileName);
					item.setValue(fileName);
					item.setCommand(MobileReaderCommands.CMD_DIR);
					item.setCommandListener(this);
					this.add(item);
					itemHeightCount += item.getHeight();
				} else {
					// this is regular file
					item = new ListItem(this.fileIcon, fileName);
					item.setValue(fileName);
					item.setCommand(MobileReaderCommands.CMD_VIEW);
					item.setCommandListener(this);
					this.add(item);
					itemHeightCount += item.getHeight();
				}
			}
			if(itemHeightCount > this.height){
				this.setPrefSize(FireScreen.getScreen().getWidth(), itemHeightCount);
			}else{
				this.setPrefSize(FireScreen.getScreen().getWidth(), FireScreen.getScreen().getHeight() - 35);
			}
			this.setPanelTitle(this.getCurrentDirName());
			if (this.parent != null) {
				FireScreen.getScreen().setCurrent(this.parent);
			}

		} catch (IOException ioe) {
			Console.WriteLine("initList failed", ioe);
		}
	}

	public void setCommandListener(CommandListener cl) {
		this.commandListener = cl;
	}

	public void setCommand(Command cmd) {
	}

	public void commandAction(Command cmd, Component c) {
		//Console.WriteLine("Command is '" + cmd.getLabel() + "'");
		//Console.WriteLine("Comonent is '" + c.getClass() + "'");
		if (cmd == MobileReaderCommands.CMD_DIR) {
			ListItem li = (ListItem) c;
			final String dirName = (String) li.getValue();
			new Thread(new Runnable() {
				public void run() {
					initList(dirName);
				}
			}).start();
		} else if (cmd == MobileReaderCommands.CMD_VIEW) {
			final String fileName = (String) ((ListItem) c).getValue();
			final String fullPath = this.fileManager.getFullPath() + fileName;
			final CommandListener cl = this;
			new Thread(new Runnable() {
				public void run() {
					viewer.cleanCurrentPage();
					Panel panel = new Panel(viewer, Panel.HORIZONTAL_SCROLLBAR
							| Panel.VERTICAL_SCROLLBAR, true);
					panel.setLayout(FireScreen.TOP | FireScreen.LEFT);
					panel.setLeftSoftKeyCommand(MobileReaderCommands.CMD_BACK);
					panel.setRightSoftKeyCommand(MobileReaderCommands.CMD_CONSOLE);
					panel.setCommandListener(cl);
					
					viewer.setParent(panel);
					viewer.setFileFullPath(fullPath);
					
					FireScreen.getScreen().setCurrent(panel);
					Console.WriteLine("This thread will be ended.");
				}
			}).start();

		} else if (cmd == MobileReaderCommands.CMD_BACK) {
			if (this.parent != null) {
				FireScreen.getScreen().setCurrent(this.parent);
			}
		}else if(cmd == MobileReaderCommands.CMD_CONSOLE){
			FireScreen.getScreen().setCurrent(Console.getInstance());
		}
	}

	public void commandAction(Command c, Displayable d) {
		// throw new UnsupportedOperationException("Not supported yet.");
	}
	
	public String getCurrentDirName(){
		return this.fileManager.getCurrentDir();
	}
	
	public void setParent(Panel p){
		this.parent = p;
	}
	public void setPanelTitle(String title){
		if(this.parent != null && this.parent instanceof Panel){
			((Panel)this.parent).setLabel(title);
		}
	}
}
