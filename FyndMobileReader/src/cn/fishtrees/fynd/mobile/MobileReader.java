package cn.fishtrees.fynd.mobile;

import gr.fire.core.BoxLayout;
import gr.fire.core.CommandListener;
import gr.fire.core.Component;
import gr.fire.core.FireScreen;
import gr.fire.core.Panel;
import gr.fire.ui.FireTheme;

import java.io.IOException;

import javax.microedition.lcdui.Command;
import javax.microedition.lcdui.Display;
import javax.microedition.lcdui.Displayable;
import javax.microedition.midlet.MIDlet;

import cn.fishtrees.fynd.mobile.ui.Console;

/**
 * @author fishtrees
 */
public class MobileReader extends MIDlet implements CommandListener {

	private FileList fileList;
	private FireScreen screen;
	
	
	public MobileReader() {
		Console.WriteLine("MobileReader constract begin");
		try {
			FireScreen.setTheme(new FireTheme(
					"file://res/themes/theme.properties"));
		} catch (IOException e) {
			Console.WriteLine("Set theme failed", e);
		}
		this.screen = FireScreen.getScreen(Display.getDisplay(this));
		this.screen.setFullScreenMode(true);
		this.screen.setOrientation(FireScreen.NORMAL);
		this.fileList = new FileList(new BoxLayout(BoxLayout.Y_AXIS));
		this.fileList.setLayout(FireScreen.TOP|FireScreen.LEFT);
		
		Console.WriteLine("MobileReader constract end");
	}

	public void startApp() {
		Console.WriteLine("MobileReader startApp begin");
		try {
			Panel panel = new Panel(this.fileList, Panel.HORIZONTAL_SCROLLBAR
					| Panel.VERTICAL_SCROLLBAR, true);
			panel.setLayout(FireScreen.TOP|FireScreen.LEFT);
			panel.setLeftSoftKeyCommand(MobileReaderCommands.CMD_EXIT);
			panel.setRightSoftKeyCommand(MobileReaderCommands.CMD_CONSOLE);
			panel.setCommandListener(this);
			
			this.fileList.setParent(panel);
			this.fileList.initList();

			this.screen.setCurrent(panel);
			
			
		} catch (Exception e) {
			Console.WriteLine("Exception:", e);
		}
		Console.WriteLine("MobileReader startApp end");
	}

	public void pauseApp() {
		Console.WriteLine("MobileReader pauseApp begin");
	}

	public void destroyApp(boolean unconditional) {
		Console.WriteLine("MobileReader destroyApp begin");
		this.notifyDestroyed();
		Console.WriteLine("MobileReader destroyApp end");
	}

	public void commandAction(Command cmd, Component c) {
		if(MobileReaderCommands.CMD_EXIT == cmd){
			this.notifyDestroyed();
			return;
		}
		if(MobileReaderCommands.CMD_CONSOLE == cmd){
			this.screen.setCurrent(Console.getInstance());
			return;
		}
	}

	public void commandAction(Command c, Displayable d) {
		// throw new UnsupportedOperationException("Not supported yet.");
	}
}
